import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\BurgerBuilderController::index
* @see app/Http/Controllers/BurgerBuilderController.php:13
* @route '/burger-builder'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/burger-builder',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\BurgerBuilderController::index
* @see app/Http/Controllers/BurgerBuilderController.php:13
* @route '/burger-builder'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\BurgerBuilderController::index
* @see app/Http/Controllers/BurgerBuilderController.php:13
* @route '/burger-builder'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\BurgerBuilderController::index
* @see app/Http/Controllers/BurgerBuilderController.php:13
* @route '/burger-builder'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\BurgerBuilderController::index
* @see app/Http/Controllers/BurgerBuilderController.php:13
* @route '/burger-builder'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\BurgerBuilderController::index
* @see app/Http/Controllers/BurgerBuilderController.php:13
* @route '/burger-builder'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\BurgerBuilderController::index
* @see app/Http/Controllers/BurgerBuilderController.php:13
* @route '/burger-builder'
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
* @see \App\Http\Controllers\BurgerBuilderController::store
* @see app/Http/Controllers/BurgerBuilderController.php:33
* @route '/burger-builder'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/burger-builder',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\BurgerBuilderController::store
* @see app/Http/Controllers/BurgerBuilderController.php:33
* @route '/burger-builder'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\BurgerBuilderController::store
* @see app/Http/Controllers/BurgerBuilderController.php:33
* @route '/burger-builder'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\BurgerBuilderController::store
* @see app/Http/Controllers/BurgerBuilderController.php:33
* @route '/burger-builder'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\BurgerBuilderController::store
* @see app/Http/Controllers/BurgerBuilderController.php:33
* @route '/burger-builder'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\BurgerBuilderController::update
* @see app/Http/Controllers/BurgerBuilderController.php:66
* @route '/burger-builder/{burger}'
*/
export const update = (args: { burger: number | { id: number } } | [burger: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put"],
    url: '/burger-builder/{burger}',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\BurgerBuilderController::update
* @see app/Http/Controllers/BurgerBuilderController.php:66
* @route '/burger-builder/{burger}'
*/
update.url = (args: { burger: number | { id: number } } | [burger: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { burger: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { burger: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            burger: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        burger: typeof args.burger === 'object'
        ? args.burger.id
        : args.burger,
    }

    return update.definition.url
            .replace('{burger}', parsedArgs.burger.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\BurgerBuilderController::update
* @see app/Http/Controllers/BurgerBuilderController.php:66
* @route '/burger-builder/{burger}'
*/
update.put = (args: { burger: number | { id: number } } | [burger: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\BurgerBuilderController::update
* @see app/Http/Controllers/BurgerBuilderController.php:66
* @route '/burger-builder/{burger}'
*/
const updateForm = (args: { burger: number | { id: number } } | [burger: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\BurgerBuilderController::update
* @see app/Http/Controllers/BurgerBuilderController.php:66
* @route '/burger-builder/{burger}'
*/
updateForm.put = (args: { burger: number | { id: number } } | [burger: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

update.form = updateForm

/**
* @see \App\Http\Controllers\BurgerBuilderController::destroy
* @see app/Http/Controllers/BurgerBuilderController.php:99
* @route '/burger-builder/{burger}'
*/
export const destroy = (args: { burger: number | { id: number } } | [burger: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/burger-builder/{burger}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\BurgerBuilderController::destroy
* @see app/Http/Controllers/BurgerBuilderController.php:99
* @route '/burger-builder/{burger}'
*/
destroy.url = (args: { burger: number | { id: number } } | [burger: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { burger: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { burger: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            burger: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        burger: typeof args.burger === 'object'
        ? args.burger.id
        : args.burger,
    }

    return destroy.definition.url
            .replace('{burger}', parsedArgs.burger.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\BurgerBuilderController::destroy
* @see app/Http/Controllers/BurgerBuilderController.php:99
* @route '/burger-builder/{burger}'
*/
destroy.delete = (args: { burger: number | { id: number } } | [burger: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\BurgerBuilderController::destroy
* @see app/Http/Controllers/BurgerBuilderController.php:99
* @route '/burger-builder/{burger}'
*/
const destroyForm = (args: { burger: number | { id: number } } | [burger: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\BurgerBuilderController::destroy
* @see app/Http/Controllers/BurgerBuilderController.php:99
* @route '/burger-builder/{burger}'
*/
destroyForm.delete = (args: { burger: number | { id: number } } | [burger: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

destroy.form = destroyForm

/**
* @see \App\Http\Controllers\BurgerBuilderController::favorite
* @see app/Http/Controllers/BurgerBuilderController.php:108
* @route '/burger-builder/{burger}/favorite'
*/
export const favorite = (args: { burger: number | { id: number } } | [burger: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: favorite.url(args, options),
    method: 'post',
})

favorite.definition = {
    methods: ["post"],
    url: '/burger-builder/{burger}/favorite',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\BurgerBuilderController::favorite
* @see app/Http/Controllers/BurgerBuilderController.php:108
* @route '/burger-builder/{burger}/favorite'
*/
favorite.url = (args: { burger: number | { id: number } } | [burger: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { burger: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { burger: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            burger: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        burger: typeof args.burger === 'object'
        ? args.burger.id
        : args.burger,
    }

    return favorite.definition.url
            .replace('{burger}', parsedArgs.burger.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\BurgerBuilderController::favorite
* @see app/Http/Controllers/BurgerBuilderController.php:108
* @route '/burger-builder/{burger}/favorite'
*/
favorite.post = (args: { burger: number | { id: number } } | [burger: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: favorite.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\BurgerBuilderController::favorite
* @see app/Http/Controllers/BurgerBuilderController.php:108
* @route '/burger-builder/{burger}/favorite'
*/
const favoriteForm = (args: { burger: number | { id: number } } | [burger: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: favorite.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\BurgerBuilderController::favorite
* @see app/Http/Controllers/BurgerBuilderController.php:108
* @route '/burger-builder/{burger}/favorite'
*/
favoriteForm.post = (args: { burger: number | { id: number } } | [burger: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: favorite.url(args, options),
    method: 'post',
})

favorite.form = favoriteForm

const burgerBuilder = {
    index: Object.assign(index, index),
    store: Object.assign(store, store),
    update: Object.assign(update, update),
    destroy: Object.assign(destroy, destroy),
    favorite: Object.assign(favorite, favorite),
}

export default burgerBuilder