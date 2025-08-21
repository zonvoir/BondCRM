# SAAS App

A simple setup and workflow guide for contributors.
---
## ⚙️ Setup Instructions
### 1. Install PHP & NPM Dependencies
```bash
composer install && npm install
```
---
### 2. Setup Environment File
```bash
cp .env.example .env
```
---
### 3. Generate Application Key
```bash
php artisan key:generate
```
---
### 4. Run Migrations and Seeders
```bash
php artisan migrate:fresh --seed
```
---
## 🛠️ Server Requirements
* **Laravel:** v12.x
* **PHP:** >= 8.3
* **inertiajs:** >= 2.x
* **Node:** >= 22.x
* **NPM:** >= 10.x
* **Vue.js:** 3.x
* **Tailwind CSS:** 4.x
* **Database:** MySQL 8.x or PostgreSQL
---
## 🎯 Code Style Fixing
### ℹ️ PHP Code Style
```bash
composer lint
```
### ℹ️ Vue / Blade Code Style
```bash
npm run lint:fix
```
---
## 🚀 Git Workflow
### ⚠️ Do NOT push directly to `main`
### ℹ️ Create a Feature Branch
```bash
git pull origin main
git checkout -b feature/your-branch-name
```
---
### 🧪 Example: Branch by Developer "Abc"
```bash
git checkout -b feature/abc-login-feature
git add .
git commit -m "Feat: Add login feature with validation - by Abc"
git push origin feature/abc-login-feature
```
---

# 🧾 Laravel CRUD Module: Customer (Example)

A structured guide to create a new CRUD module following the architecture and best practices.

---

## 📁 Folder Structure Overview

```
app/
├── Http/
│   ├── Controllers/
│   │   └── Customer/
│   │       └── CustomerController.php
│   ├── Requests/
│   │   └── Customer/
│   │       └── CustomerRequest.php
│   ├── Resources/
│   │   └── Customer/
│   │       └── CustomerResource.php
├── Models/
│   └── Customer.php
├── Repositories/
│   └── Customer/
│       └── CustomerRepository.php
├── Services/
│   └── Customer/
│       └── CustomerService.php

resources/
└── js/
    └── Pages/
        └── Customer/
            ├── index.vue
            └── Partials/
                ├── Create.vue
                └── List.vue
```

---

## 🛠️ Step-by-Step Workflow

### 1. 📦 Create Model & Migration

```bash
php artisan make:model Customer -m
```

Edit the migration:

```php
Schema::create('customers', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->timestamps();
});
```

Run migration:

```bash
php artisan migrate
```

---

### 2. 📂 Create Controller

```bash
php artisan make:controller Customer/CustomerController
```

---

### 3. 📝 Create Request Class for Validation

```bash
php artisan make:request Customer/CustomerRequest
```

Example:

```php
public function rules(): array
{
    return [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:customers,email,' . $this->id,
    ];
}
```

---

### 4. 🧠 Create Resource for API Response

```bash
php artisan make:resource Customer/CustomerResource
```

Example:

```php
public function toArray($request): array
{
    return [
        'id' => $this->id,
        'name' => $this->name,
        'email' => $this->email,
    ];
}
```

---

### 5. 📁 Create Repository

`app/Repositories/Customer/CustomerRepository.php`

Encapsulate all database logic:

```php
class CustomerRepository
{
    public function all() {
        return Customer::latest()->get();
    }

    public function find($id) {
        return Customer::findOrFail($id);
    }

    public function create(array $data) {
        return Customer::create($data);
    }

    public function update(Customer $customer, array $data) {
        $customer->update($data);
        return $customer;
    }

    public function delete(Customer $customer) {
        return $customer->delete();
    }
}
```

---

### 6. 🔧 Create Service

`app/Services/Customer/CustomerService.php`

Handles business logic:

```php
class CustomerService
{
    protected $repository;

    public function __construct(CustomerRepository $repository)
    {
        $this->repository = $repository;
    }

    public function save(array $data, $id = null)
    {
        if ($id) {
            $customer = $this->repository->find($id);
            return $this->repository->update($customer, $data);
        }
        return $this->repository->create($data);
    }
}
```

---

### 7. 🔗 Setup Routes

In `routes/web.php`:

```php
use App\Http\Controllers\Customer\CustomerController;

Route::prefix('customer')->controller(CustomerController::class)->group(function () {
    Route::get('/', 'show')->name('customer.index');
    Route::post('customer-save', 'customerSave')->name('customer.save');
});
```

---

### 8. 🧑‍💻 Create Vue Pages

**Path:** `resources/js/Pages/Customer/`

#### `index.vue`

```vue
<script setup>
import List from './Partials/List.vue'
import Create from './Partials/Create.vue'
</script>

<template>
  <h1 class="text-2xl font-bold">Customer Management</h1>
  <Create />
  <List />
</template>
```

#### `Partials/Create.vue`

```vue
<script setup>

</script>

<template>
  
</template>
```

#### `Partials/List.vue`

```vue
<script setup>
defineProps({ customers: Array })
</script>

<template>
  <ul>
    <li v-for="customer in customers" :key="customer.id">
      {{ customer.name }} - {{ customer.email }}
    </li>
  </ul>
</template>
```

---

### 9. 🧪 Sample Controller Code

```php
class CustomerController extends Controller
{
    protected $service;

    public function __construct(protected CustomerService $service)
    {
      
    }

    public function show()
    {
        $customers = CustomerResource::collection(Customer::latest()->get());
        return Inertia::render('Customer/index', compact('customers'));
    }

    public function customerSave(CustomerRequest $request)
    {
        $this->service->save($request->validated(), $request->id);
       
    }
}
```

---

### ✅ Summary of Steps

| Step | Task                     |
| ---- | ------------------------ |
| 1    | Create Model & Migration |
| 2    | Make Controller          |
| 3    | Create Request Class     |
| 4    | Create API Resource      |
| 5    | Build Repository         |
| 6    | Build Service            |
| 7    | Define Routes            |
| 8    | Build Vue Pages          |
| 9    | Add Logic in Controller  |

---

---
## ✅ Commit Message Convention
Follow the **Conventional Commits** format:
```
type: short description
```
### ℹ️ Types
* **Feat:** New feature
* **Fix:** Bug fix
* **Docs:** Documentation changes
* **Style:** Formatting only (no logic change)
* **Refactor:** Code improvement (no new features or bugs)
* **Test:** Add or update tests
* **Chore:** Other tasks (builds, dependencies, etc.)
### 📝 Example Commit Messages
```bash
git commit -m "Feat: Add login functionality"
git commit -m "Fix: Resolve user session bug"
git commit -m "Docs: Update README for setup steps"
git commit -m "Style: Format login component code"
```

## ⚠️ Codecanyon Coding Style Guidelines

### 🔰 Basic Guidelines
* ✅ Follow consistent file/folder structure according to Laravel conventions
* ✅ Use `.env` for all environment-specific configuration
* ✅ Follow PSR-12 coding standard for PHP files
* ✅ Validate all user inputs properly with Form Requests or `validate()`
* ✅ Escape all Blade outputs using `{{ }}` (never use `{!! !!}` unless necessary)
* ✅ Use descriptive variable and function names
* ✅ Remove all debugging code (`dd()`, `dump()`, `console.log()`) before deployment
* ✅ Add comments for complex logic blocks
* ❌ Don't commit sensitive data or credentials
* ❌ Don't leave commented-out code in production
* ❌ Don't use inline SQL queries (use Eloquent or Query Builder)
* ❌ Don't skip input validation on any user-submitted data

### 🔄 Intermediate Guidelines
* ✅ Use named routes and route groups with appropriate middleware
* ✅ Implement proper error handling with try-catch blocks for external services
* ✅ Use Laravel's authorization policies for access control
* ✅ Implement proper pagination for list views
* ✅ Use Laravel's queue system for long-running tasks
* ✅ Optimize database queries (eager loading, chunking for large datasets)
* ✅ Use Laravel's form request validation for complex validation rules
* ✅ Create dedicated view composers for complex views
* ✅ Use event listeners for decoupled code
* ❌ Don't use global variables or functions
* ❌ Don't use unoptimized or large external packages without evaluation
* ❌ Don't load unnecessary JavaScript libraries on every page
* ❌ Don't create controllers with mixed responsibilities

### 🚀 Advanced Guidelines
* ✅ Implement proper caching strategies (Redis or Memcached)
* ✅ Use Laravel's API resources for consistent JSON responses
* ✅ Write unit and feature tests for critical functionality
* ✅ Use value objects and DTOs for complex data structures
* ✅ Implement repository pattern for database abstraction when needed
* ✅ Use Laravel's service container for dependency injection
* ✅ Optimize asset loading with proper bundling and minification
* ✅ Implement rate limiting for public APIs
* ✅ Create custom validation rules for complex validation
* ✅ Use Laravel's middleware pipeline effectively
* ❌ Don't implement business logic in controllers (use services/actions)
* ❌ Don't create god classes with too many responsibilities
* ❌ Don't ignore N+1 query problems
* ❌ Don't skip indexing frequently queried database columns

### 💻 Frontend Guidelines
* ✅ Use TailwindCSS utility classes consistently
* ✅ Implement responsive design for all UI components
* ✅ Extract reusable Vue components
* ✅ Use Inertia's persistent layouts for consistent UX
* ✅ Lazy load components and routes when possible
* ✅ Implement proper form validation feedback on the frontend
* ✅ Use proper state management for complex applications
* ✅ Optimize image assets (compression, responsive sizes)
* ❌ Don't use inline styles (use Tailwind classes)
* ❌ Don't rely on JavaScript alert() or prompt() for user interaction
* ❌ Don't mix different CSS frameworks
* ❌ Don't create overly complex components with mixed responsibilities

### ⚡ Inertia.js with Vue.js Guidelines
* ✅ Use shared layouts with `createInertiaApp`
* ✅ Implement page components in `resources/js/Pages` directory
* ✅ Follow Vue 3 Composition API patterns for complex components
* ✅ Use typed props with `defineProps<{...}>()` for better type safety
* ✅ Leverage Inertia's form helpers for form submissions
* ✅ Use `usePage()` to access shared data across components
* ✅ Implement proper loading states with Inertia's progress indicator
* ✅ Structure page components with clear separation of concerns
* ✅ Use Vue's `<Suspense>` for async components when appropriate
* ✅ Leverage Inertia's `Head` component for dynamic page titles and meta
* ✅ Use shared Vue composables for reusable logic
* ✅ Implement proper error handling in form submissions
* ❌ Don't mix Inertia page navigation with traditional links
* ❌ Don't overuse `$page` in deeply nested components (pass props instead)
* ❌ Don't send large data payloads through Inertia props
* ❌ Don't duplicate API calls that could be included in initial page load
* ❌ Don't use global state when component props are sufficient
* ❌ Don't use jQuery or other DOM manipulation libraries with Vue components
* ❌ Don't bypass the Inertia lifecycle with direct XHR/fetch requests
* ❌ Don't create tightly coupled components that assume Inertia context

### 🔒 Security Guidelines
* ✅ Use CSRF protection for all forms
* ✅ Implement proper authentication checks on all routes
* ✅ Sanitize file uploads (validate mime types, size limits)
* ✅ Use Laravel's mass assignment protection
* ✅ Implement proper CORS policies
* ✅ Use secure HTTP headers (Content-Security-Policy, X-Frame-Options)
* ✅ Implement proper session management
* ✅ Use prepared statements for all database queries
* ❌ Don't store sensitive data in local storage or cookies
* ❌ Don't expose sensitive server information in responses
* ❌ Don't trust client-side validation alone
* ❌ Don't use vulnerable package versions

### 📦 Deployment Guidelines
* ✅ Run PHP and JavaScript linters before deployment
* ✅ Create a comprehensive deployment checklist
* ✅ Implement proper error logging and monitoring
* ✅ Use proper database indexing for production
* ✅ Optimize autoloading with Composer optimization
* ✅ Generate up-to-date documentation
* ✅ Create detailed changelogs for each release
* ✅ Test on multiple environments before releasing
* ❌ Don't deploy during peak usage hours
* ❌ Don't skip database backups before major updates
* ❌ Don't leave debug mode enabled in production
