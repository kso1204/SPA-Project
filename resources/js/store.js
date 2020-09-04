import Vue from 'vue'
import Vuex from 'vuex'
Vue.use(Vuex)

export default new Vuex.Store({
    // ...
    state : {
        counter : 1000,
        deleteModalObj:{
            showDeleteModal : false,
            deleteUrl : '',
            data : null,
            deletingIndex: -1,
            isDeleted: false,
        },
        user:false,
    },

    getters: {
        getCounter(state){
            return state.counter
            /*
            if(state.counter > 1000) return 'o my god, this is big...'
            return 'ok that is small'*/
        },
        getCounterByHalf(state){
            return state.counter/2
        },
        getDeleteModalObj(state){
            return state.deleteModalObj
        }
    },

    mutations: {
        changeTheCounter(state, data){
            state.counter += data
            console.log(data);            
        },

        setDeleteModal(state, data){

            const deleteModalObj = {
                showDeleteModal : false,
                deleteUrl : '',
                data : null,
                deletingIndex : -1,
                isDeleted : data,
            }

            state.deleteModalObj = deleteModalObj
        },

        setDeletingModalObj(state,data){
            state.deleteModalObj = data
            //tags에서 showdeletemodal했을 때 그 정보를 변환시켜서? 필요한 정보를 
            //state 상태로 저장하는 부분 url설정~ modal 열리는 부분~ index data 등등
        },
        updateUser(state,data){
            state.user = data
        },
    },
    
    actions :{
        changeCounterAction({commit},data){
            //console.log(commit)
            commit('changeTheCounter',data)
        }
    }



})