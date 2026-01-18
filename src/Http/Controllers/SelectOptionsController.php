<?php

declare(strict_types=1);

namespace Accelade\Forms\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SelectOptionsController extends Controller
{
    /**
     * Get a single record by ID for editing.
     *
     * Returns the record data for use in edit forms.
     */
    public function show(Request $request): JsonResponse
    {
        $request->validate([
            'id' => 'required|string',
        ]);

        // Get configuration from validated token payload (set by middleware)
        $tokenPayload = $request->input('_select_token_payload', []);

        if (empty($tokenPayload)) {
            return response()->json([
                'error' => 'Invalid request',
            ], 400);
        }

        $modelClass = $tokenPayload['model'];
        $valueAttribute = $tokenPayload['value_attribute'] ?? 'id';
        $editAttributes = $tokenPayload['edit_attributes'] ?? null;

        $id = $request->input('id');

        /** @var Model $modelInstance */
        $modelInstance = new $modelClass;
        $record = $modelInstance->newQuery()->where($valueAttribute, $id)->first();

        if (! $record) {
            return response()->json([
                'error' => 'Record not found',
            ], 404);
        }

        // If specific edit attributes are defined, only return those
        if ($editAttributes && is_array($editAttributes)) {
            $data = [];
            foreach ($editAttributes as $attr) {
                $data[$attr] = $record->{$attr};
            }
        } else {
            // Return all fillable attributes plus the value attribute
            $data = $record->toArray();
        }

        return response()->json([
            'data' => $data,
        ]);
    }

    /**
     * Get paginated options for a select field.
     *
     * All configuration is extracted from the encrypted token that was
     * validated by the ValidateSelectToken middleware.
     */
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'page' => 'nullable|integer|min:1',
            'search' => 'nullable|string|max:255',
        ]);

        // Get configuration from validated token payload (set by middleware)
        $tokenPayload = $request->input('_select_token_payload', []);

        if (empty($tokenPayload)) {
            return response()->json([
                'error' => 'Invalid request',
                'data' => [],
                'hasMore' => false,
            ], 400);
        }

        $modelClass = $tokenPayload['model'];
        $labelAttribute = $tokenPayload['label_attribute'] ?? 'name';
        $valueAttribute = $tokenPayload['value_attribute'] ?? 'id';
        $searchColumns = $tokenPayload['search_columns'] ?? $labelAttribute;
        $perPage = (int) ($tokenPayload['per_page'] ?? 50);

        $page = (int) $request->input('page', 1);
        $search = $request->input('search');

        /** @var Model $modelInstance */
        $modelInstance = new $modelClass;
        $query = $modelInstance->newQuery();

        // Apply search filter
        if ($search !== null && $search !== '') {
            $columns = is_array($searchColumns) ? $searchColumns : explode(',', $searchColumns);
            $query->where(function ($q) use ($columns, $search) {
                foreach ($columns as $index => $column) {
                    $column = trim($column);
                    if ($index === 0) {
                        $q->where($column, 'like', "%{$search}%");
                    } else {
                        $q->orWhere($column, 'like', "%{$search}%");
                    }
                }
            });
        }

        // Paginate results
        $paginator = $query->paginate($perPage, [$valueAttribute, $labelAttribute], 'page', $page);

        // Format for JavaScript
        $data = collect($paginator->items())->map(function ($item) use ($labelAttribute, $valueAttribute) {
            return [
                'value' => (string) $item->{$valueAttribute},
                'label' => $item->{$labelAttribute},
            ];
        })->all();

        return response()->json([
            'data' => $data,
            'hasMore' => $paginator->hasMorePages(),
            'total' => $paginator->total(),
            'currentPage' => $paginator->currentPage(),
            'lastPage' => $paginator->lastPage(),
            'perPage' => $paginator->perPage(),
        ]);
    }

    /**
     * Create a new record.
     */
    public function store(Request $request): JsonResponse
    {
        // Get configuration from validated token payload (set by middleware)
        $tokenPayload = $request->input('_select_token_payload', []);

        if (empty($tokenPayload)) {
            return response()->json([
                'error' => 'Invalid request',
            ], 400);
        }

        $modelClass = $tokenPayload['model'];
        $labelAttribute = $tokenPayload['label_attribute'] ?? 'name';
        $valueAttribute = $tokenPayload['value_attribute'] ?? 'id';
        $createAttributes = $tokenPayload['create_attributes'] ?? null;
        $validationRules = $tokenPayload['validation_rules'] ?? [];

        // Validate incoming data
        if (! empty($validationRules)) {
            $request->validate($validationRules);
        }

        // Get data to create
        $data = $request->except(['_token', '_select_token_payload']);

        // If specific create attributes are defined, only use those
        if ($createAttributes && is_array($createAttributes)) {
            $data = array_intersect_key($data, array_flip($createAttributes));
        }

        /** @var Model $modelInstance */
        $modelInstance = new $modelClass;
        $record = $modelInstance->newQuery()->create($data);

        return response()->json([
            'success' => true,
            'data' => [
                'value' => (string) $record->{$valueAttribute},
                'label' => $record->{$labelAttribute},
            ],
        ], 201);
    }

    /**
     * Update an existing record.
     */
    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'id' => 'required|string',
        ]);

        // Get configuration from validated token payload (set by middleware)
        $tokenPayload = $request->input('_select_token_payload', []);

        if (empty($tokenPayload)) {
            return response()->json([
                'error' => 'Invalid request',
            ], 400);
        }

        $modelClass = $tokenPayload['model'];
        $labelAttribute = $tokenPayload['label_attribute'] ?? 'name';
        $valueAttribute = $tokenPayload['value_attribute'] ?? 'id';
        $editAttributes = $tokenPayload['edit_attributes'] ?? null;
        $validationRules = $tokenPayload['validation_rules'] ?? [];

        $id = $request->input('id');

        // Validate incoming data (excluding id and token fields)
        if (! empty($validationRules)) {
            $request->validate($validationRules);
        }

        /** @var Model $modelInstance */
        $modelInstance = new $modelClass;
        $record = $modelInstance->newQuery()->where($valueAttribute, $id)->first();

        if (! $record) {
            return response()->json([
                'error' => 'Record not found',
            ], 404);
        }

        // Get data to update
        $data = $request->except(['_token', '_select_token_payload', 'id']);

        // If specific edit attributes are defined, only update those
        if ($editAttributes && is_array($editAttributes)) {
            $data = array_intersect_key($data, array_flip($editAttributes));
        }

        $record->update($data);

        return response()->json([
            'success' => true,
            'data' => [
                'value' => (string) $record->{$valueAttribute},
                'label' => $record->{$labelAttribute},
            ],
        ]);
    }
}
