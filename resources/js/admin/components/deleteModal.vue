<template>
    <div>
        <Modal
        :value="getDeleteModalObj.showDeleteModal"
        title="Common Modal dialog box title"
		:mask-closable = "false"
		:closable = "false"
         width="360">
            <p slot="header" style="color:#f60;text-align:center">
                <Icon type="ios-information-circle"></Icon>
                <span>Delete confirmation</span>
            </p>
            <div style="text-align:center">
                <p>After this task is deleted, the downstream 10 tasks will not be implemented.</p>
                <p>Will you delete it?</p>
            </div>
            <div slot="footer">
                <Button type="default"     @click="closeModal">close</Button>
                <Button type="error"   :loading="isDeleting" :disabled="isDeleting"  @click="deleteTag">Delete</Button>
            </div>
        </Modal>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex'
    export default {
        data()
        {
            return {
                isDeleting:false,
            }
        },

        methods:{
            async deleteTag(){

                //this.$store.commit('setDeleteModal')

                this.isDeleting=true
                //this.isDeleting=true
                //this.$set(deleteModal, 'isDeleting', true)
                //여기서 작동해도 안 된다.. ㅠㅠ
                const res = await this.callApi('post', this.getDeleteModalObj.deleteUrl, this.getDeleteModalObj.data)
                if(res.status===200)
                {
                    //this.tags.splice(this.deletingIndex,1)
                    //이 부분을 왜 해주는걸까? 배열의 위치 삭제
                    //i번째의 데이터를 1개 삭제한다
                    //그래서 반응형으로 바로 보여줌
                    //삭제된 데이터를 바로 보여줄 때
                    this.s('Tag has been delete succesfully!')
                    this.$store.commit('setDeleteModal', true)
                }else{
                    this.swr()
                    this.$store.commit('setDeleteModal', false)
                }

                //this.deleteModal=false
                this.isDeleting =false
            },
            closeModal(){
                 this.$store.commit('setDeleteModal', false)
            }
        },

        computed: {
            ...mapGetters(['getDeleteModalObj'])
        },
        

    }

</script>