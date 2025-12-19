<?php

namespace App\Traits;

/**
 * Trait for generating action buttons in DataTables
 *
 * This trait provides flexible methods to generate action buttons
 * for DataTables across the application, supporting both modal-based
 * and route-based actions with permission checks.
 */
trait GeneratesActionButtons
{
    /**
     * Generate action buttons HTML for DataTables
     *
     * @param int|string $id The record ID
     * @param array $options Configuration options
     * @return string HTML string for action buttons
     */
    public function generateActionButtons(int|string $id, array $options = []): string
    {
        $defaults = [
            'edit' => true,
            'delete' => true,
            'view' => false,
            'edit_route' => null,
            'delete_route' => null,
            'view_route' => null,
            'edit_function' => null,
            'delete_function' => null,
            'view_function' => null,
            'edit_permission' => null,
            'delete_permission' => null,
            'view_permission' => null,
            'edit_class' => 'btn btn-sm btn-primary',
            'delete_class' => 'btn btn-sm btn-danger',
            'view_class' => 'btn btn-sm btn-info',
            'edit_icon' => 'ri-edit-line',
            'delete_icon' => 'ri-delete-bin-line',
            'view_icon' => 'ri-eye-line',
            'edit_title' => 'Edit',
            'delete_title' => 'Delete',
            'view_title' => 'View',
            'container_class' => 'd-flex gap-2',
            'data_table' => null,
        ];

        $options = array_merge($defaults, $options);
        $buttons = [];

        // Generate View button
        if ($options['view']) {
            $viewButton = $this->generateButton(
                id: $id,
                type: 'view',
                route: $options['view_route'],
                function: $options['view_function'],
                permission: $options['view_permission'],
                class: $options['view_class'],
                icon: $options['view_icon'],
                title: $options['view_title'],
                dataTable: $options['data_table']
            );

            if ($viewButton) {
                $buttons[] = $viewButton;
            }
        }

        // Generate Edit button
        if ($options['edit']) {
            $editButton = $this->generateButton(
                id: $id,
                type: 'edit',
                route: $options['edit_route'],
                function: $options['edit_function'],
                permission: $options['edit_permission'],
                class: $options['edit_class'],
                icon: $options['edit_icon'],
                title: $options['edit_title'],
                dataTable: $options['data_table']
            );

            if ($editButton) {
                $buttons[] = $editButton;
            }
        }

        // Generate Delete button
        if ($options['delete']) {
            $deleteButton = $this->generateButton(
                id: $id,
                type: 'delete',
                route: $options['delete_route'],
                function: $options['delete_function'],
                permission: $options['delete_permission'],
                class: $options['delete_class'],
                icon: $options['delete_icon'],
                title: $options['delete_title'],
                dataTable: $options['data_table']
            );

            if ($deleteButton) {
                $buttons[] = $deleteButton;
            }
        }

        if (empty($buttons)) {
            return '<span class="text-muted">N/A</span>';
        }

        return '<div class="' . htmlspecialchars($options['container_class'], ENT_QUOTES, 'UTF-8') . '">' .
               implode('', $buttons) .
               '</div>';
    }

    /**
     * Generate a single action button
     *
     * @param int|string $id The record ID
     * @param string $type Button type (edit, delete, view)
     * @param string|null $route Route name or URL
     * @param string|null $function JavaScript function name
     * @param string|null $permission Permission name to check
     * @param string $class CSS classes for the button
     * @param string $icon Icon class name
     * @param string $title Button title/tooltip
     * @param string|null $dataTable DataTable identifier for delete operations
     * @return string|null HTML string for the button or null if permission denied
     */
    private function generateButton(
        int|string $id,
        string $type,
        ?string $route = null,
        ?string $function = null,
        ?string $permission = null,
        string $class = 'btn btn-sm',
        string $icon = '',
        string $title = '',
        ?string $dataTable = null
    ): ?string {
        // Check permission if provided
        if ($permission) {
            $user = \Illuminate\Support\Facades\Auth::user();
            if (! $user || (method_exists($user, 'hasPermission') && ! $user->hasPermission($permission))) {
                return null;
            }
        }

        $idEscaped = htmlspecialchars((string)$id, ENT_QUOTES, 'UTF-8');
        $classEscaped = htmlspecialchars($class, ENT_QUOTES, 'UTF-8');
        $iconEscaped = htmlspecialchars($icon, ENT_QUOTES, 'UTF-8');
        $titleEscaped = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');

        // Route-based button
        if ($route) {
            $url = str_starts_with($route, 'http') ? $route : route($route, $id);
            $urlEscaped = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');

            if ($type === 'delete') {
                $dataTableAttr = $dataTable ? ' data-table="' . htmlspecialchars($dataTable, ENT_QUOTES, 'UTF-8') . '"' : '';
                return '<a href="' . $urlEscaped . '"' .
                       $dataTableAttr .
                       ' class="' . $classEscaped . ' delete-record" title="' . $titleEscaped . '">' .
                       '<i class="' . $iconEscaped . '"></i></a>';
            }

            return '<a href="' . $urlEscaped . '" class="' . $classEscaped . '" title="' . $titleEscaped . '">' .
                   '<i class="' . $iconEscaped . '"></i></a>';
        }

        // Function-based button (for modals)
        if ($function) {
            $functionEscaped = htmlspecialchars($function, ENT_QUOTES, 'UTF-8');
            return '<button onclick="' . $functionEscaped . '(' . $idEscaped . ')" ' .
                   'class="' . $classEscaped . '" title="' . $titleEscaped . '">' .
                   '<i class="' . $iconEscaped . '"></i></button>';
        }

        return null;
    }

    /**
     * Generate action buttons with modal-based edit/delete
     *
     * @param int|string $id The record ID
     * @param string $entityType Entity type name (e.g., 'Country', 'State')
     * @param array $options Additional options
     * @return string HTML string for action buttons
     */
    public function generateModalActionButtons(int|string $id, string $entityType, array $options = []): string
    {
        $defaults = [
            'edit_function' => "open{$entityType}Modal",
            'delete_function' => "delete{$entityType}",
            'edit_permission' => null,
            'delete_permission' => null,
        ];

        $mergedOptions = array_merge($defaults, $options);
        $buttons = [];

        // Generate Edit button
        $editButton = $this->generateButton(
            id: $id,
            type: 'edit',
            route: null,
            function: $mergedOptions['edit_function'],
            permission: $mergedOptions['edit_permission'],
            class: 'btn btn-sm btn-primary',
            icon: 'ri-edit-line',
            title: 'Edit',
            dataTable: null
        );

        if ($editButton) {
            $buttons[] = $editButton;
        }

        // Generate Delete button
        $deleteButton = $this->generateButton(
            id: $id,
            type: 'delete',
            route: null,
            function: $mergedOptions['delete_function'],
            permission: $mergedOptions['delete_permission'],
            class: 'btn btn-sm btn-danger',
            icon: 'ri-delete-bin-line',
            title: 'Delete',
            dataTable: null
        );

        if ($deleteButton) {
            $buttons[] = $deleteButton;
        }

        if (empty($buttons)) {
            return '<span class="text-muted">N/A</span>';
        }

        return '<div class="d-flex gap-2">' . implode('', $buttons) . '</div>';
    }

    /**
     * Generate action buttons with route-based edit/delete
     *
     * @param int|string $id The record ID
     * @param string $routePrefix Route name prefix (e.g., 'countries' for 'countries.edit', 'countries.destroy')
     * @param array $options Additional options
     * @return string HTML string for action buttons
     */
    public function generateRouteActionButtons(int|string $id, string $routePrefix, array $options = []): string
    {
        $defaults = [
            'edit_route' => "{$routePrefix}.edit",
            'delete_route' => "{$routePrefix}.destroy",
            'edit_permission' => "edit-{$routePrefix}",
            'delete_permission' => "delete-{$routePrefix}",
            'data_table' => "{$routePrefix}-data-table",
        ];

        $mergedOptions = array_merge($defaults, $options);
        $buttons = [];

        // Generate Edit button
        $editButton = $this->generateButton(
            id: $id,
            type: 'edit',
            route: $mergedOptions['edit_route'],
            function: null,
            permission: $mergedOptions['edit_permission'],
            class: 'btn btn-sm btn-primary',
            icon: 'ri-edit-line',
            title: 'Edit',
            dataTable: null
        );

        if ($editButton) {
            $buttons[] = $editButton;
        }

        // Generate Delete button
        $deleteButton = $this->generateButton(
            id: $id,
            type: 'delete',
            route: $mergedOptions['delete_route'],
            function: null,
            permission: $mergedOptions['delete_permission'],
            class: 'btn btn-sm btn-danger',
            icon: 'ri-delete-bin-line',
            title: 'Delete',
            dataTable: $mergedOptions['data_table']
        );

        if ($deleteButton) {
            $buttons[] = $deleteButton;
        }

        if (empty($buttons)) {
            return '<span class="text-muted">N/A</span>';
        }

        return '<div class="d-flex gap-2">' . implode('', $buttons) . '</div>';
    }
}
