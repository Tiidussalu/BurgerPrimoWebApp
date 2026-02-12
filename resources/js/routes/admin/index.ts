import dashboard from './dashboard'
import orders from './orders'
import menu from './menu'

const admin = {
    dashboard: Object.assign(dashboard, dashboard),
    orders: Object.assign(orders, orders),
    menu: Object.assign(menu, menu),
}

export default admin