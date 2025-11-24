import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\OrderController::index
* @see app/Http/Controllers/Admin/OrderController.php:23
* @route '/admin/orders'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/admin/orders',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\OrderController::index
* @see app/Http/Controllers/Admin/OrderController.php:23
* @route '/admin/orders'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\OrderController::index
* @see app/Http/Controllers/Admin/OrderController.php:23
* @route '/admin/orders'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\OrderController::index
* @see app/Http/Controllers/Admin/OrderController.php:23
* @route '/admin/orders'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\OrderController::index
* @see app/Http/Controllers/Admin/OrderController.php:23
* @route '/admin/orders'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\OrderController::index
* @see app/Http/Controllers/Admin/OrderController.php:23
* @route '/admin/orders'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\OrderController::index
* @see app/Http/Controllers/Admin/OrderController.php:23
* @route '/admin/orders'
*/
indexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

index.form = indexForm

/**
* @see \App\Http\Controllers\Admin\OrderController::show
* @see app/Http/Controllers/Admin/OrderController.php:49
* @route '/admin/orders/{order}'
*/
export const show = (args: { order: number | { id: number } } | [order: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/admin/orders/{order}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\OrderController::show
* @see app/Http/Controllers/Admin/OrderController.php:49
* @route '/admin/orders/{order}'
*/
show.url = (args: { order: number | { id: number } } | [order: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { order: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { order: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            order: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        order: typeof args.order === 'object'
        ? args.order.id
        : args.order,
    }

    return show.definition.url
            .replace('{order}', parsedArgs.order.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\OrderController::show
* @see app/Http/Controllers/Admin/OrderController.php:49
* @route '/admin/orders/{order}'
*/
show.get = (args: { order: number | { id: number } } | [order: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\OrderController::show
* @see app/Http/Controllers/Admin/OrderController.php:49
* @route '/admin/orders/{order}'
*/
show.head = (args: { order: number | { id: number } } | [order: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\OrderController::show
* @see app/Http/Controllers/Admin/OrderController.php:49
* @route '/admin/orders/{order}'
*/
const showForm = (args: { order: number | { id: number } } | [order: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\OrderController::show
* @see app/Http/Controllers/Admin/OrderController.php:49
* @route '/admin/orders/{order}'
*/
showForm.get = (args: { order: number | { id: number } } | [order: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\OrderController::show
* @see app/Http/Controllers/Admin/OrderController.php:49
* @route '/admin/orders/{order}'
*/
showForm.head = (args: { order: number | { id: number } } | [order: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

show.form = showForm

/**
* @see \App\Http\Controllers\Admin\OrderController::confirm
* @see app/Http/Controllers/Admin/OrderController.php:82
* @route '/admin/orders/{order}/confirm'
*/
export const confirm = (args: { order: number | { id: number } } | [order: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: confirm.url(args, options),
    method: 'post',
})

confirm.definition = {
    methods: ["post"],
    url: '/admin/orders/{order}/confirm',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\OrderController::confirm
* @see app/Http/Controllers/Admin/OrderController.php:82
* @route '/admin/orders/{order}/confirm'
*/
confirm.url = (args: { order: number | { id: number } } | [order: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { order: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { order: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            order: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        order: typeof args.order === 'object'
        ? args.order.id
        : args.order,
    }

    return confirm.definition.url
            .replace('{order}', parsedArgs.order.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\OrderController::confirm
* @see app/Http/Controllers/Admin/OrderController.php:82
* @route '/admin/orders/{order}/confirm'
*/
confirm.post = (args: { order: number | { id: number } } | [order: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: confirm.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\OrderController::confirm
* @see app/Http/Controllers/Admin/OrderController.php:82
* @route '/admin/orders/{order}/confirm'
*/
const confirmForm = (args: { order: number | { id: number } } | [order: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: confirm.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\OrderController::confirm
* @see app/Http/Controllers/Admin/OrderController.php:82
* @route '/admin/orders/{order}/confirm'
*/
confirmForm.post = (args: { order: number | { id: number } } | [order: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: confirm.url(args, options),
    method: 'post',
})

confirm.form = confirmForm

/**
* @see \App\Http\Controllers\Admin\OrderController::status
* @see app/Http/Controllers/Admin/OrderController.php:58
* @route '/admin/orders/{order}/status'
*/
export const status = (args: { order: number | { id: number } } | [order: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: status.url(args, options),
    method: 'post',
})

status.definition = {
    methods: ["post"],
    url: '/admin/orders/{order}/status',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\OrderController::status
* @see app/Http/Controllers/Admin/OrderController.php:58
* @route '/admin/orders/{order}/status'
*/
status.url = (args: { order: number | { id: number } } | [order: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { order: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { order: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            order: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        order: typeof args.order === 'object'
        ? args.order.id
        : args.order,
    }

    return status.definition.url
            .replace('{order}', parsedArgs.order.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\OrderController::status
* @see app/Http/Controllers/Admin/OrderController.php:58
* @route '/admin/orders/{order}/status'
*/
status.post = (args: { order: number | { id: number } } | [order: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: status.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\OrderController::status
* @see app/Http/Controllers/Admin/OrderController.php:58
* @route '/admin/orders/{order}/status'
*/
const statusForm = (args: { order: number | { id: number } } | [order: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: status.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\OrderController::status
* @see app/Http/Controllers/Admin/OrderController.php:58
* @route '/admin/orders/{order}/status'
*/
statusForm.post = (args: { order: number | { id: number } } | [order: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: status.url(args, options),
    method: 'post',
})

status.form = statusForm

/**
* @see \App\Http\Controllers\Admin\OrderController::bulkStatus
* @see app/Http/Controllers/Admin/OrderController.php:93
* @route '/admin/orders/bulk-status'
*/
export const bulkStatus = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: bulkStatus.url(options),
    method: 'post',
})

bulkStatus.definition = {
    methods: ["post"],
    url: '/admin/orders/bulk-status',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\OrderController::bulkStatus
* @see app/Http/Controllers/Admin/OrderController.php:93
* @route '/admin/orders/bulk-status'
*/
bulkStatus.url = (options?: RouteQueryOptions) => {
    return bulkStatus.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\OrderController::bulkStatus
* @see app/Http/Controllers/Admin/OrderController.php:93
* @route '/admin/orders/bulk-status'
*/
bulkStatus.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: bulkStatus.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\OrderController::bulkStatus
* @see app/Http/Controllers/Admin/OrderController.php:93
* @route '/admin/orders/bulk-status'
*/
const bulkStatusForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: bulkStatus.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\OrderController::bulkStatus
* @see app/Http/Controllers/Admin/OrderController.php:93
* @route '/admin/orders/bulk-status'
*/
bulkStatusForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: bulkStatus.url(options),
    method: 'post',
})

bulkStatus.form = bulkStatusForm

const orders = {
    index: Object.assign(index, index),
    show: Object.assign(show, show),
    confirm: Object.assign(confirm, confirm),
    status: Object.assign(status, status),
    bulkStatus: Object.assign(bulkStatus, bulkStatus),
}

export default orders