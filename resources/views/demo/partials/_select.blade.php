{{-- Select Component Section --}}
@props(['prefix' => 'a'])

@php
    use Accelade\Forms\Components\Select;
    use Accelade\Forms\Components\TextInput;
    use App\Models\User;

    // Select with Create/Edit Modal Forms (using User model)
    $selectWithCreateEdit = Select::make('user_id_modal')
        ->label('User (with Create/Edit)')
        ->options(User::query()->orderBy('name')->limit(20)->pluck('name', 'id')->toArray())
        ->allowClear()
        ->emptyOptionLabel('Select user...')
        ->createOptionForm([
            TextInput::make('name')
                ->label('Full Name')
                ->required(),
            TextInput::make('email')
                ->label('Email Address')
                ->required()
                ->helperText('Must be a valid email'),
        ])
        ->createOptionModalHeading('Create New User')
        ->createOptionModalSubmitButtonLabel('Create User')
        ->editOptionForm([
            TextInput::make('name')
                ->label('Full Name')
                ->required(),
            TextInput::make('email')
                ->label('Email Address')
                ->required(),
        ])
        ->editOptionModalHeading('Edit User')
        ->editOptionModalSubmitButtonLabel('Update User')
        ->helperText('Click + to create, pencil to edit selected');

    // Searchable select (default)
    $countrySelect = Select::make('country')
        ->label('Country')
        ->options([
            'us' => 'United States',
            'uk' => 'United Kingdom',
            'ca' => 'Canada',
            'au' => 'Australia',
            'de' => 'Germany',
            'fr' => 'France',
            'jp' => 'Japan',
            'br' => 'Brazil',
            'in' => 'India',
            'mx' => 'Mexico',
        ])
        ->allowClear()
        ->emptyOptionLabel('Select a country...');

    // Options from User model (limited to 20 for quick loading)
    $usersFromModel = User::query()
        ->orderBy('name')
        ->limit(20)
        ->pluck('name', 'id')
        ->toArray();

    $userSelect = Select::make('user_id')
        ->label('Select User (from Model)')
        ->options($usersFromModel)
        ->allowClear()
        ->searchPlaceholder('Search users...')
        ->emptyOptionLabel('Choose a user...')
        ->helperText('Options loaded from User model using pluck()');

    // Using relationship method (Filament style)
    $userRelationSelect = Select::make('assigned_to')
        ->label('Assign To (Relationship)')
        ->relationship('user', 'name')
        ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->name} ({$record->email})")
        ->searchPlaceholder('Search by name or email...')
        ->emptyOptionLabel('Select assignee...')
        ->helperText('Using relationship() with custom label from record');

    // With getOptionsUsing callback
    $dynamicUserSelect = Select::make('reviewer_id')
        ->label('Reviewer (Dynamic Options)')
        ->getOptionsUsing(fn () => User::query()
            ->orderBy('name')
            ->limit(50)
            ->pluck('name', 'id')
            ->toArray()
        )
        ->allowClear()
        ->emptyOptionLabel('Select reviewer...')
        ->helperText('Options loaded via getOptionsUsing() callback');

    // With option descriptions from model
    $userWithDescriptionSelect = Select::make('manager_id')
        ->label('Manager (with Email)')
        ->options($usersFromModel)
        ->getOptionDescriptionUsing(fn ($value) => User::find($value)?->email)
        ->allowClear()
        ->emptyOptionLabel('Select manager...')
        ->helperText('Description shows user email');

    // Multiple users selection
    $multipleUsersSelect = Select::make('team_members')
        ->label('Team Members')
        ->options($usersFromModel)
        ->multiple()
        ->closeOnSelect(false)
        ->helperText('Select multiple team members');

    // ===== PAGINATION & INFINITE SCROLL =====

    // Paginated select with infinite scroll (default limit: 50)
    $paginatedUserSelect = Select::make('paginated_user_id')
        ->label('User (Paginated - 50 items)')
        ->model(User::class, 'name', 'id')  // Model with label/value attributes
        ->limit(50)  // Show 50 items initially (default)
        ->searchPlaceholder('Search users...')
        ->emptyOptionLabel('Select user...')
        ->helperText('Scroll down to load more users (infinite scroll)');

    // Paginated with custom limit
    $paginatedLimitedSelect = Select::make('limited_user_id')
        ->label('User (Limited to 10)')
        ->model(User::class, 'name', 'id')
        ->limit(10)  // Only show 10 items per page
        ->searchPlaceholder('Search...')
        ->emptyOptionLabel('Select user...')
        ->helperText('Shows 10 items at a time, scroll to load more');

    // Select with default value outside first page (demonstrates auto-fetching selected value)
    // Get a user ID that would be outside the first page (e.g., ID 100+)
    $userOutsideFirstPage = User::query()->skip(100)->first();
    $defaultUserId = $userOutsideFirstPage?->id ?? 1;

    $selectWithDefaultValue = Select::make('preselected_user_id')
        ->label('Pre-selected User (Default Value)')
        ->model(User::class, 'name', 'id')
        ->limit(10)  // Only load first 10, but default is outside this range
        ->default($defaultUserId)  // This user won't be in first 10, but will be auto-fetched
        ->allowClear()
        ->emptyOptionLabel('Select user...')
        ->helperText('Default value is auto-fetched even if outside first page');

    // Show all options (no pagination) - using static options for demo
    // Note: Don't use all() with 10,000+ records - it will be slow!
    $allUsersSelect = Select::make('all_users_id')
        ->label('All Options (No Pagination)')
        ->options([
            '1' => 'Option 1',
            '2' => 'Option 2',
            '3' => 'Option 3',
            '4' => 'Option 4',
            '5' => 'Option 5',
            '6' => 'Option 6',
            '7' => 'Option 7',
            '8' => 'Option 8',
            '9' => 'Option 9',
            '10' => 'Option 10',
        ])
        ->all()  // Show all options, no infinite scroll
        ->searchPlaceholder('Search...')
        ->emptyOptionLabel('Select option...')
        ->helperText('all() shows everything at once - use for small datasets only');

    // Searchable with custom placeholder
    $languageSelect = Select::make('language')
        ->label('Programming Language')
        ->options([
            'php' => 'PHP',
            'javascript' => 'JavaScript',
            'python' => 'Python',
            'rust' => 'Rust',
            'go' => 'Go',
            'java' => 'Java',
            'csharp' => 'C#',
            'ruby' => 'Ruby',
            'swift' => 'Swift',
            'kotlin' => 'Kotlin',
        ])
        ->searchPlaceholder('Type to search...')
        ->noResultsText('No languages found')
        ->allowClear()
        ->emptyOptionLabel('Choose a language...');

    // Multiple select with tags
    $skillsSelect = Select::make('skills')
        ->label('Skills')
        ->options([
            'php' => 'PHP',
            'javascript' => 'JavaScript',
            'python' => 'Python',
            'rust' => 'Rust',
            'go' => 'Go',
            'typescript' => 'TypeScript',
            'java' => 'Java',
            'ruby' => 'Ruby',
        ])
        ->multiple()
        ->closeOnSelect(false)
        ->helperText('Select multiple skills');

    // Multiple with max selections
    $topSkillsSelect = Select::make('top_skills')
        ->label('Top 3 Skills')
        ->options([
            'leadership' => 'Leadership',
            'communication' => 'Communication',
            'problem_solving' => 'Problem Solving',
            'teamwork' => 'Teamwork',
            'creativity' => 'Creativity',
            'adaptability' => 'Adaptability',
        ])
        ->multiple()
        ->maxSelections(3)
        ->helperText('Select up to 3 skills');

    // Taggable select (create new options)
    $tagsSelect = Select::make('tags')
        ->label('Tags')
        ->options([
            'featured' => 'Featured',
            'new' => 'New',
            'popular' => 'Popular',
        ])
        ->taggable()
        ->multiple()
        ->createOptionText('Create "{value}"')
        ->helperText('Type and press Enter to create new tags');

    // Native select
    $nativeSelect = Select::make('status')
        ->label('Status')
        ->native()
        ->options([
            'draft' => 'Draft',
            'pending' => 'Pending Review',
            'published' => 'Published',
            'archived' => 'Archived',
        ])
        ->emptyOptionLabel('Select status...')
        ->helperText('Uses native browser select');

    // Grouped options
    $groupedSelect = Select::make('vehicle')
        ->label('Vehicle')
        ->groupedOptions([
            'Cars' => [
                'sedan' => 'Sedan',
                'suv' => 'SUV',
                'sports' => 'Sports Car',
            ],
            'Motorcycles' => [
                'cruiser' => 'Cruiser',
                'sport' => 'Sport Bike',
                'touring' => 'Touring',
            ],
            'Trucks' => [
                'pickup' => 'Pickup',
                'semi' => 'Semi-Truck',
                'box' => 'Box Truck',
            ],
        ])
        ->emptyOptionLabel('Select a vehicle...')
        ->helperText('Options organized in groups');

    // With option descriptions
    $planSelect = Select::make('plan')
        ->label('Subscription Plan')
        ->options([
            'free' => 'Free',
            'pro' => 'Pro',
            'enterprise' => 'Enterprise',
        ])
        ->getOptionDescriptionUsing(fn ($value) => match($value) {
            'free' => 'Basic features, limited usage',
            'pro' => '$29/month - Advanced features',
            'enterprise' => 'Custom pricing - Full features',
            default => null,
        })
        ->emptyOptionLabel('Select a plan...')
        ->helperText('Each option shows additional details');

    // Disabled options
    $prioritySelect = Select::make('priority')
        ->label('Priority Level')
        ->options([
            'low' => 'Low',
            'medium' => 'Medium',
            'high' => 'High',
            'urgent' => 'Urgent (Admin Only)',
        ])
        ->disableOptionWhen(fn ($value) => $value === 'urgent')
        ->emptyOptionLabel('Select priority...')
        ->helperText('Some options may be disabled based on permissions');

    // Boolean select
    $activeSelect = Select::make('is_active')
        ->label('Active Status')
        ->boolean('Active', 'Inactive', 'Select status...')
        ->helperText('Simple Yes/No style select');

    // With prefix
    $searchSelect = Select::make('search_category')
        ->label('Search in Category')
        ->prefixIcon('magnifying-glass')
        ->options([
            'all' => 'All Categories',
            'products' => 'Products',
            'services' => 'Services',
            'blog' => 'Blog Posts',
        ])
        ->emptyOptionLabel('Select category...');

    // With prefix text
    $currencySelect = Select::make('currency')
        ->label('Currency')
        ->prefix('$')
        ->options([
            'usd' => 'US Dollar',
            'eur' => 'Euro',
            'gbp' => 'British Pound',
            'jpy' => 'Japanese Yen',
        ])
        ->emptyOptionLabel('Select currency...');

    // Dependent/Cascading Selects Demo Data
    $countries = [
        'us' => 'United States',
        'ca' => 'Canada',
        'uk' => 'United Kingdom',
    ];

    $statesByCountry = [
        'us' => [
            'ca' => 'California',
            'tx' => 'Texas',
            'ny' => 'New York',
            'fl' => 'Florida',
        ],
        'ca' => [
            'on' => 'Ontario',
            'qc' => 'Quebec',
            'bc' => 'British Columbia',
            'ab' => 'Alberta',
        ],
        'uk' => [
            'eng' => 'England',
            'sct' => 'Scotland',
            'wls' => 'Wales',
            'nir' => 'Northern Ireland',
        ],
    ];

    $citiesByState = [
        'ca' => ['la' => 'Los Angeles', 'sf' => 'San Francisco', 'sd' => 'San Diego'],
        'tx' => ['houston' => 'Houston', 'dallas' => 'Dallas', 'austin' => 'Austin'],
        'ny' => ['nyc' => 'New York City', 'buffalo' => 'Buffalo', 'albany' => 'Albany'],
        'fl' => ['miami' => 'Miami', 'orlando' => 'Orlando', 'tampa' => 'Tampa'],
        'on' => ['toronto' => 'Toronto', 'ottawa' => 'Ottawa', 'mississauga' => 'Mississauga'],
        'qc' => ['montreal' => 'Montreal', 'quebec_city' => 'Quebec City', 'laval' => 'Laval'],
        'bc' => ['vancouver' => 'Vancouver', 'victoria' => 'Victoria', 'surrey' => 'Surrey'],
        'ab' => ['calgary' => 'Calgary', 'edmonton' => 'Edmonton', 'red_deer' => 'Red Deer'],
        'eng' => ['london' => 'London', 'manchester' => 'Manchester', 'birmingham' => 'Birmingham'],
        'sct' => ['edinburgh' => 'Edinburgh', 'glasgow' => 'Glasgow', 'aberdeen' => 'Aberdeen'],
        'wls' => ['cardiff' => 'Cardiff', 'swansea' => 'Swansea', 'newport' => 'Newport'],
        'nir' => ['belfast' => 'Belfast', 'derry' => 'Derry', 'lisburn' => 'Lisburn'],
    ];

    // Dependent selects
    $dependentCountrySelect = Select::make('dependent_country')
        ->label('Country')
        ->options($countries)
        ->emptyOptionLabel('Select country first...')
        ->helperText('Step 1: Select a country');

    $dependentStateSelect = Select::make('dependent_state')
        ->label('State/Province')
        ->options([])  // Options loaded dynamically via JavaScript
        ->emptyOptionLabel('Select state...')
        ->helperText('Step 2: Options depend on country');

    $dependentCitySelect = Select::make('dependent_city')
        ->label('City')
        ->options([])  // Options loaded dynamically via JavaScript
        ->emptyOptionLabel('Select city...')
        ->helperText('Step 3: Options depend on state');
@endphp

<section class="rounded-xl p-6 mb-6 border border-[var(--docs-border)]" style="background: var(--docs-bg-alt);">
    <div class="flex items-center gap-3 mb-2">
        <span class="w-2.5 h-2.5 bg-indigo-500 rounded-full"></span>
        <h3 class="text-lg font-semibold" style="color: var(--docs-text);">Select</h3>
    </div>
    <p class="text-sm mb-4" style="color: var(--docs-text-muted);">
        Advanced searchable select with keyboard navigation, multiple selection, tagging, grouped options, model integration, and dependent selects.
    </p>

    <div class="space-y-4 mb-4">
        <!-- Create/Edit Option Modal -->
        <div class="rounded-xl p-4 border border-green-500/30" style="background: rgba(34, 197, 94, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-green-500/20 text-green-500 rounded">Modal</span>
                Create & Edit Users in Modal
            </h4>

            {!! $selectWithCreateEdit !!}
            <p class="text-xs mt-2" style="color: var(--docs-text-muted);">
                Click the <strong>+</strong> button to create new users, or select a user and click the <strong>pencil</strong> icon to edit.
            </p>
        </div>

        <!-- Model-Based Options -->
        <div class="rounded-xl p-4 border border-blue-500/30" style="background: rgba(59, 130, 246, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-blue-500/20 text-blue-500 rounded">Model</span>
                Options from Eloquent Model
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {!! $userSelect !!}
                {!! $userWithDescriptionSelect !!}
            </div>
            <p class="text-xs mt-2" style="color: var(--docs-text-muted);">
                Load options directly from database using Eloquent models.
            </p>
        </div>

        <!-- Multiple Users from Model -->
        <div class="rounded-xl p-4 border border-violet-500/30" style="background: rgba(139, 92, 246, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-violet-500/20 text-violet-500 rounded">Multiple</span>
                Multiple Selection from Model
            </h4>

            {!! $multipleUsersSelect !!}
        </div>

        <!-- Pagination & Infinite Scroll -->
        <div class="rounded-xl p-4 border border-lime-500/30" style="background: rgba(132, 204, 22, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-lime-500/20 text-lime-500 rounded">Pagination</span>
                Infinite Scroll & Pagination
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {!! $paginatedUserSelect !!}
                {!! $paginatedLimitedSelect !!}
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                {!! $selectWithDefaultValue !!}
                {!! $allUsersSelect !!}
            </div>
            <p class="text-xs mt-2" style="color: var(--docs-text-muted);">
                <strong>model()->limit(50)</strong> - Load 50 users with infinite scroll. <strong>limit(10)</strong> - Custom page size. <strong>default()</strong> - Auto-fetches selected value. <strong>all()</strong> - For small datasets only.
            </p>
        </div>

        <!-- Dependent/Cascading Selects -->
        <div class="rounded-xl p-4 border border-sky-500/30" style="background: rgba(14, 165, 233, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-sky-500/20 text-sky-500 rounded">Dependent</span>
                Cascading Selects (Country → State → City)
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4" id="dependent-selects-demo">
                {!! $dependentCountrySelect !!}
                {!! $dependentStateSelect !!}
                {!! $dependentCitySelect !!}
            </div>
            <p class="text-xs mt-2" style="color: var(--docs-text-muted);">
                Each select depends on the previous selection. Options update automatically.
            </p>

            {{-- Dependent Selects Data and JavaScript --}}
            <script>
                (function() {
                    const statesByCountry = @json($statesByCountry);
                    const citiesByState = @json($citiesByState);

                    document.addEventListener('DOMContentLoaded', function() {
                        const container = document.getElementById('dependent-selects-demo');
                        if (!container) return;

                        const countrySelect = container.querySelector('[name="dependent_country"]');
                        const stateSelect = container.querySelector('[name="dependent_state"]');
                        const citySelect = container.querySelector('[name="dependent_city"]');

                        if (!countrySelect || !stateSelect || !citySelect) return;

                        // Get the wrapper elements for searchable selects
                        const countryWrapper = countrySelect.closest('.select-field');
                        const stateWrapper = stateSelect.closest('.select-field');
                        const cityWrapper = citySelect.closest('.select-field');

                        function updateOptions(selectElement, options, wrapper) {
                            // Clear current options (keep first empty option)
                            const firstOption = selectElement.querySelector('option[value=""]');
                            selectElement.innerHTML = '';
                            if (firstOption) {
                                selectElement.appendChild(firstOption);
                            }

                            // Add new options
                            Object.entries(options).forEach(([value, label]) => {
                                const option = document.createElement('option');
                                option.value = value;
                                option.textContent = label;
                                selectElement.appendChild(option);
                            });

                            // Update searchable select dropdown if it exists
                            if (wrapper) {
                                const dropdown = wrapper.querySelector('.searchable-select-options');
                                const display = wrapper.querySelector('.searchable-select-display');
                                if (dropdown) {
                                    dropdown.innerHTML = '';
                                    Object.entries(options).forEach(([value, label]) => {
                                        const li = document.createElement('li');
                                        li.className = 'searchable-select-option px-3 py-2 text-sm cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-900 dark:text-gray-100';
                                        li.setAttribute('data-value', value);
                                        li.setAttribute('role', 'option');
                                        li.innerHTML = `<div class="flex items-center justify-between"><div class="flex-1 truncate"><span class="searchable-select-option-label">${label}</span></div><span class="searchable-select-option-check hidden ms-2 text-primary-600 shrink-0"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg></span></div>`;
                                        dropdown.appendChild(li);
                                    });

                                    // Re-initialize click handlers
                                    if (typeof window.initSearchableSelects === 'function') {
                                        window.initSearchableSelects();
                                    }
                                }
                                if (display) {
                                    display.textContent = selectElement.options[0]?.textContent || 'Select...';
                                }
                            }
                        }

                        // Country change handler
                        countrySelect.addEventListener('change', function() {
                            const country = this.value;
                            const states = statesByCountry[country] || {};

                            // Update state options
                            updateOptions(stateSelect, states, stateWrapper);
                            stateSelect.value = '';

                            // Clear city options
                            updateOptions(citySelect, {}, cityWrapper);
                            citySelect.value = '';
                        });

                        // State change handler
                        stateSelect.addEventListener('change', function() {
                            const state = this.value;
                            const cities = citiesByState[state] || {};

                            // Update city options
                            updateOptions(citySelect, cities, cityWrapper);
                            citySelect.value = '';
                        });

                        // Handle searchable select changes
                        if (countryWrapper) {
                            countryWrapper.addEventListener('searchable-select-change', function(e) {
                                const country = e.detail.value;
                                const states = statesByCountry[country] || {};
                                updateOptions(stateSelect, states, stateWrapper);
                                updateOptions(citySelect, {}, cityWrapper);
                            });
                        }

                        if (stateWrapper) {
                            stateWrapper.addEventListener('searchable-select-change', function(e) {
                                const state = e.detail.value;
                                const cities = citiesByState[state] || {};
                                updateOptions(citySelect, cities, cityWrapper);
                            });
                        }
                    });
                })();
            </script>
        </div>

        <!-- Searchable Select (Default) -->
        <div class="rounded-xl p-4 border border-indigo-500/30" style="background: rgba(99, 102, 241, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-indigo-500/20 text-indigo-500 rounded">Searchable</span>
                Searchable Select (Default)
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {!! $countrySelect !!}
                {!! $languageSelect !!}
            </div>
            <p class="text-xs mt-2" style="color: var(--docs-text-muted);">
                Type to search options. Use arrow keys to navigate, Enter to select.
            </p>
        </div>

        <!-- Grouped Options -->
        <div class="rounded-xl p-4 border border-purple-500/30" style="background: rgba(168, 85, 247, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-purple-500/20 text-purple-500 rounded">Grouped</span>
                Grouped Options
            </h4>

            {!! $groupedSelect !!}
        </div>

        <!-- Option Descriptions -->
        <div class="rounded-xl p-4 border border-cyan-500/30" style="background: rgba(6, 182, 212, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-cyan-500/20 text-cyan-500 rounded">Descriptions</span>
                Options with Descriptions
            </h4>

            {!! $planSelect !!}
        </div>

        <!-- Disabled Options -->
        <div class="rounded-xl p-4 border border-orange-500/30" style="background: rgba(249, 115, 22, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-orange-500/20 text-orange-500 rounded">Disabled</span>
                Disabled Options
            </h4>

            {!! $prioritySelect !!}
        </div>

        <!-- Multiple Select -->
        <div class="rounded-xl p-4 border border-amber-500/30" style="background: rgba(245, 158, 11, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-amber-500/20 text-amber-500 rounded">Multiple</span>
                Multiple Selection with Tags
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {!! $skillsSelect !!}
                {!! $topSkillsSelect !!}
            </div>
        </div>

        <!-- Taggable Select -->
        <div class="rounded-xl p-4 border border-emerald-500/30" style="background: rgba(16, 185, 129, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-emerald-500/20 text-emerald-500 rounded">Taggable</span>
                Create New Options
            </h4>

            {!! $tagsSelect !!}
        </div>

        <!-- Boolean Select -->
        <div class="rounded-xl p-4 border border-pink-500/30" style="background: rgba(236, 72, 153, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-pink-500/20 text-pink-500 rounded">Boolean</span>
                Boolean Yes/No Select
            </h4>

            {!! $activeSelect !!}
        </div>

        <!-- With Prefix -->
        <div class="rounded-xl p-4 border border-teal-500/30" style="background: rgba(20, 184, 166, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-teal-500/20 text-teal-500 rounded">Affixes</span>
                With Prefix Icon/Text
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {!! $searchSelect !!}
                {!! $currencySelect !!}
            </div>
        </div>

        <!-- Native Select -->
        <div class="rounded-xl p-4 border border-rose-500/30" style="background: rgba(244, 63, 94, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-rose-500/20 text-rose-500 rounded">Native</span>
                Browser Native Select
            </h4>

            {!! $nativeSelect !!}
        </div>
    </div>

    <x-accelade::code-block language="php" filename="select-examples.php">
use Accelade\Forms\Components\Select;
use Accelade\Forms\Components\TextInput;
use App\Models\User;

// ===== CREATE/EDIT OPTIONS IN MODAL =====

// Add create and edit forms to select (using User model)
Select::make('user_id')
    ->label('User')
    ->options(User::pluck('name', 'id')->toArray())
    ->allowClear()
    ->createOptionForm([
        TextInput::make('name')
            ->label('Full Name')
            ->required(),
        TextInput::make('email')
            ->label('Email')
            ->required(),
    ])
    ->createOptionModalHeading('Create New User')
    ->createOptionAction(function (array $data): int {
        $data['password'] = bcrypt('password');
        return User::create($data)->getKey();
    })
    ->editOptionForm([
        TextInput::make('name')
            ->label('Full Name')
            ->required(),
        TextInput::make('email')
            ->label('Email')
            ->required(),
    ])
    ->editOptionModalHeading('Edit User')
    ->updateOptionUsing(function (array $data, $record) {
        $record->update($data);
    });

// ===== OPTIONS FROM ELOQUENT MODEL =====

// Simple: Load options using pluck()
Select::make('user_id')
    ->label('Select User')
    ->options(User::pluck('name', 'id')->toArray())
    ->searchPlaceholder('Search users...');

// Dynamic: Load via callback (lazy loading)
Select::make('reviewer_id')
    ->label('Reviewer')
    ->getOptionsUsing(fn () => User::orderBy('name')
        ->pluck('name', 'id')
        ->toArray()
    );

// With descriptions from model
Select::make('manager_id')
    ->label('Manager')
    ->options(User::pluck('name', 'id')->toArray())
    ->getOptionDescriptionUsing(fn ($value) => User::find($value)?->email);

// Using relationship (Filament-style)
Select::make('assigned_to')
    ->label('Assign To')
    ->relationship('user', 'name')
    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->name} ({$record->email})");

// ===== PAGINATION & INFINITE SCROLL =====

// Default: 50 items with infinite scroll
Select::make('user_id')
    ->label('Select User')
    ->model(User::class, 'name', 'id')  // Model, label attribute, value attribute
    ->limit(50);  // Default limit, can be omitted

// Custom limit with infinite scroll
Select::make('user_id')
    ->label('Select User')
    ->model(User::class, 'name', 'id')
    ->limit(10)  // Show 10 items per page
    ->searchColumn('name');  // Column to search on

// With default value (auto-fetched even if outside first page)
Select::make('user_id')
    ->label('Select User')
    ->model(User::class, 'name', 'id')
    ->limit(10)
    ->default(500);  // User #500 will be auto-fetched and included in options

// Show all options (no pagination)
Select::make('user_id')
    ->label('All Users')
    ->model(User::class, 'name', 'id')
    ->all();  // Disable pagination, load all

// With search on multiple columns
Select::make('user_id')
    ->label('Select User')
    ->model(User::class, 'name', 'id')
    ->limit(50)
    ->searchColumn('name,email');  // Search both columns

// ===== DEPENDENT/CASCADING SELECTS =====

// For dependent selects, use remoteUrl with dynamic values:
Select::make('country')
    ->label('Country')
    ->options($countries);

Select::make('state')
    ->label('State')
    ->remoteUrl('/api/states/${form.country}')  // Dynamic URL
    ->resetOnNewRemoteUrl();  // Clear when country changes

Select::make('city')
    ->label('City')
    ->remoteUrl('/api/cities/${form.state}')
    ->resetOnNewRemoteUrl();

// ===== OTHER FEATURES =====

// Searchable select (default behavior)
Select::make('country')
    ->label('Country')
    ->options([
        'us' => 'United States',
        'ca' => 'Canada',
    ])
    ->allowClear()
    ->emptyOptionLabel('Select...');

// Grouped options
Select::make('vehicle')
    ->groupedOptions([
        'Cars' => ['sedan' => 'Sedan', 'suv' => 'SUV'],
        'Motorcycles' => ['cruiser' => 'Cruiser'],
    ]);

// Options with descriptions
Select::make('plan')
    ->options(['free' => 'Free', 'pro' => 'Pro'])
    ->getOptionDescriptionUsing(fn ($value) => match($value) {
        'free' => 'Basic features',
        'pro' => '$29/month - Advanced',
        default => null,
    });

// Multiple select with tags
Select::make('skills')
    ->multiple()
    ->closeOnSelect(false)
    ->maxSelections(5)
    ->options($skills);

// Taggable (create new options)
Select::make('tags')
    ->taggable()
    ->multiple()
    ->createOptionText('Create "{value}"')
    ->options($existingTags);

// Boolean yes/no select
Select::make('is_active')
    ->boolean('Yes', 'No', 'Select...');

// Native browser select
Select::make('status')
    ->native()
    ->options($statuses);
    </x-accelade::code-block>
</section>
