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
}
