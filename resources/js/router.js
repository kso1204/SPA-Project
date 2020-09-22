import Vue from 'vue'
import Router from 'vue-router'
Vue.use(Router)

import firstPage from './components/pages/myFirstVuePage'
import newRoutePage from './components/pages/newRoutePage'
import hooks from './components/pages/basic/hooks.vue'
import methods from './components/pages/basic/methods.vue'

//project pages
import home from './components/pages/basic/home'
import tags from './admin/pages/tags'
import category from './admin/pages/category'
import adminusers from './admin/pages/adminusers' 
import login from './admin/pages/login' 
import role from './admin/pages/role' 
import assignRole from './admin/pages/assignRole' 
import createBlog from './admin/pages/createBlog'
import blogs from './admin/pages/blogs'
import editBlog from './admin/pages/editBlog'
import notfound from './admin/pages/notfound'


import usecom from './vuex/usecom'

const routes = [
    //projects routes...


    { 
        path: '/testvuex',
        component: usecom
    },

    { 
        path: '/',
        component: home,
        name : 'home',
    },

    
    
    { 
        path: '/tags',
        component: tags,
        name : 'tags',
    },

    
    { 
        path: '/category',
        component: category,
        name : 'category',
    },


    { 
        path: '/adminusers',
        component: adminusers,
        name : 'adminusers',
    },
    { 
        path: '/login',
        component: login,
        name : 'login',
    },
    
    { 
        path: '/role',
        component: role,
        name : 'role',
    },
    
    { 
        path: '/assignRole',
        component: assignRole,
        name : 'assignRole',
    },


    //basic tutorials routes...

    {
        path: '/my-new-vue-route',
        component: firstPage
    },
    {
        path: '/new-route',
        component: newRoutePage
    },

    // vue hooks
    {
        path: '/hooks',
        component: hooks
    },
    // more basisc
    {
        path: '/methods',
        component: methods
    },

    {
        path: '/createBlog',
        component: createBlog,
        name: 'createBlog'

    },

    {
        path: '/blogs',
        component: blogs,
        name: 'blogs'

    },
    {
        path: "/editBlog/:id",
        component: editBlog,
        name: 'editBlog'
    },
    {
        path: "*",
        component: notfound,
        name: 'notfound'
    },

]



export default new Router({
    mode: 'history',
    routes
})