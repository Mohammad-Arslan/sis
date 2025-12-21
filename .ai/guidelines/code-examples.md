# Code Examples & Patterns

## Service Class Pattern
```php
<?php

namespace App\Services;

class ExampleService
{
    public function processData(array $data): array
    {
        // Business logic here
        return $processed;
    }
}
```

## Form Request Pattern
```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExampleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'field' => 'required|string|max:255',
        ];
    }
}
```

## Controller Pattern
```php
<?php

namespace App\Http\Controllers;

use App\Services\ExampleService;
use App\Http\Requests\ExampleRequest;

class ExampleController extends Controller
{
    public function __construct(
        public ExampleService $service
    ) {}

    public function store(ExampleRequest $request)
    {
        $result = $this->service->processData($request->validated());
        return response()->json($result);
    }
}
```




