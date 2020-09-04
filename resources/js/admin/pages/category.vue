<template>
    <div>
        <div class="content">
			<div class="container-fluid">
				
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Category <Button @click="addModal=true"><Icon type="md-add" /> Add Category</Button> </p>

					<div class="_overflow _table_div">
						<table class="_table">
								<!-- TABLE TITLE -->
							<tr>
								<th>ID</th>
								<th>Icon Image</th>
								<th>Category name</th>
								<th>Created at</th>
								<th>Action</th>
							</tr>
								<!-- TABLE TITLE -->


								<!-- ITEMS -->
							<tr v-for="(category,i) in categoryLists" :key="i" v-if="categoryLists.length">
								<td>{{category.id}}</td>
								<td class="table_image">
                                    <img :src="category.iconImage">
                                    </td>
								<td class="_table_name">{{category.categoryName}}</td>
								<td>{{category.created_at}}</td>
								<td>
									<Button type="info" size="small" @click="showEditModal(category)">Edit</Button>
    								<Button type="error" size="small" @click="showDeleteModal(category, i)" :loading="category.isDeleting">Delete</Button>
								</td>
							</tr>
								<!-- ITEMS -->


						</table>
					</div>


				</div>
				 <Page :total="100" />
				
				<!-- tag adding modal -->
				<Modal 
        v-model="addModal"
        title="Category"
		:mask-closable = "false"
		:closable = "false"
		>

        
		<Input v-model="data.categoryName" placeholder="Add Tag name..." />

       <div class="space">
    </div>
            <Upload 
            ref="uploads"
            type="drag"
            :headers="{'x-csrf-token':token, 'X-Requested-With' : 'XMLHttpRequest'}"
        :on-success="handleSuccess"
        :on-error="handleError"
        :format="['jpg','jpeg','png']"
        :on-format-error="handleFormatError"
        :max-size="2048"
        :on-exceeded-size="handleMaxSize"
            action="/app/upload">
            <div style="padding: 20px 0">
                <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                <p>Click or drag files here to upload</p>
            </div>
        </Upload>

        <div class="demo-upload-list" v-if="data.iconImage"> 

            <img :src ="data.iconImage"/>
            <div class="demo-upload-list-cover table_image">
                <Icon type="ios-trash-outline" @click.native="deleteImage()"></Icon>
            </div>
        </div>

		 <div slot="footer">
			<button type="error" @click="addModal=false">close</button>
			<button type="primary" @click="addCategory" :disabled="isAdding" :loading="isAdding" >{{ isAdding? "Adding.." : "Add Category" }}</button>
		</div>
    </Modal>

<!-- tag editing modal -->


<Modal
        v-model="editModal"
        title="Category"
		:mask-closable = "false"
		:closable = "false"
		>

        
		<Input v-model="editData.categoryName" placeholder="edit Category name..." />

       <div class="space">
    </div>
            
            <Upload v-show="isIconImageNew"
            ref="editDatauploads"
            type="drag"
            :headers="{'x-csrf-token':token, 'X-Requested-With' : 'XMLHttpRequest'}"
        :on-success="handleSuccess"
        :on-error="handleError"
        :format="['jpg','jpeg','png']"
        :on-format-error="handleFormatError"
        :max-size="2048"
        :on-exceeded-size="handleMaxSize"
            action="/app/upload">
            <div style="padding: 20px 0">
                <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                <p>Click or drag files here to upload</p>
            </div>
        </Upload>

        <div class="demo-upload-list" v-if="editData.iconImage"> 

            <img :src ="editData.iconImage"/>
            <div class="demo-upload-list-cover table_image">
                <Icon type="ios-trash-outline" @click.native="deleteImage(false)"></Icon>
            </div>
        </div>

		 <div slot="footer">
			<button type="error" @click="closeEditModal">close</button>
			<button type="primary" @click="editCategory" :disabled="isAdding" :loading="isAdding" >{{ isAdding? "editing.." : "edit Category" }}</button>
		</div>
    </Modal>
	<!-- tag delete modal -->

	<!-- <Modal v-model="deleteModal" width="360">
        <p slot="header" style="color:#f60;text-align:center">
            <Icon type="ios-information-circle"></Icon>
            <span>Delete confirmation</span>
        </p>
        <div style="text-align:center">
            <p>After this task is deleted, the downstream 10 tasks will not be implemented.</p>
            <p>Will you delete it?</p>
        </div>
        <div slot="footer">
            <Button type="error" size="large" long :loading="isDeleting" :disabled="isDeleting"  @click="deleteTag">Delete</Button>
        </div>
    </Modal> -->
			<deleteModal></deleteModal>
			</div>
		</div>
    </div>
</template>

<script>

import deleteModal from '../components/deleteModal'
import {mapGetters} from 'vuex'

	export default {
		
	data(){
		return {
			data : {
                categoryName: '',
                iconImage :'',
			},
			addModal : false,
			editModal : false,
			isAdding : false,
            tags: [],
            categoryLists: [],
			editData :{
                categoryName: '',
                iconImage :'',
			},
			index: -1,
			deleteModal : false,
			deleteItem: {},
			index: -1,
            isDeleting: false,
            token:'',
            isIconImageNew:false,
            isEditingItem:false,

		}
	},

	methods :{
		async addCategory(){
			if(this.data.categoryName.trim()=='') return this.e(' Category is Required')
            if(this.data.iconImage.trim()=='') return this.e('Icon is Required')
            this.data.iconImage = `${this.data.iconImage}`
			const res = await this.callApi('post', 'app/create_category', this.data)
			if(res.status===201){
                this.categoryLists.unshift(res.data);
                //unshift는 배열에 앞요소를 추가한다고 하는데
                //list에 해당 내용을 추가해서 바로 보여지게 하는 부분이다
				this.s('Tag has been added Success')
				this.addModal=false
				this.data.categoryName= ''
				this.data.iconImage= ''
			}else{
				if(res.status==422){
					if(res.data.errors.categoryName){
						this.i(res.data.errors.categoryName[0])
                    }
                    
                    if(res.data.errors.iconImage){
						this.i(res.data.errors.iconImage[0])
					}
					
				}else{
					this.swr()
				}
			}
        },
        
		async editCategory(){
			this.editData.iconImage = `${this.editData.iconImage}`
			const res = await this.callApi('post', 'app/edit_category', this.editData)
			if(res.status===200){
                
				this.s('Tag has been edit Success')
                this.editModal=false
			}else{
				if(res.status==422){
					if(res.data.errors.categoryName){
						this.i(res.data.errors.categoryName[0])
                    }
                    
                    if(res.data.errors.iconImage){
						this.i(res.data.errors.iconImage[0])
					}
					
				}else{
					this.swr()
				}
			}
		},
		
		showEditModal(category){
            console.log('edit')
            console.log(category)
			this.editData = category
			//this.editData = tag;
            this.editModal=true
            this.isEditingItem= true
		},showDeleteModal(category, i){

			const deleteModalObj = {
				showDeleteModal : true,
				deleteUrl : 'app/delete_category',
				data : category,
				deletingIndex: i,
				isDeleted: false,
			}

			this.$store.commit('setDeletingModalObj', deleteModalObj)

			console.log('delete method called')
			/* 
			this.deleteModal=true
			//this.$set(deleteModal, 'isDeleting', true)
			//위에는 이 부분이 안 된다.. 제대로 이해를 못한듯?
			this.deleteItem = category
			this.index = i */

		},/* 
		async deleteTag(){
			this.isDeleting=true
			//this.$set(deleteModal, 'isDeleting', true)
			//여기서 작동해도 안 된다.. ㅠㅠ
			const res = await this.callApi('post', 'app/delete_tag', this.deleteItem)
			if(res.status===200)
			{
				this.tags.splice(this.index,1)
				//이 부분을 왜 해주는걸까? 배열의 위치 삭제
				//i번째의 데이터를 1개 삭제한다
				//그래서 반응형으로 바로 보여줌
				//삭제된 데이터를 바로 보여줄 때
				this.s('Tag has been delete succesfully!')
			}else{
				this.swr()
			}

			this.deleteModal=false
			this.isDeleting =false
        }, */
        
        handleSuccess (res, file) {
            res = `/uploads/${res}`
            if(this.isEditingItem)
            {
                return this.editData.iconImage = res
            }
            this.data.iconImage = res
			console.log(res)
            //file.url = 'https://o5wwk8baw.qnssl.com/7eb99afb9d5f317c912f08b5212fd69a/avatar';
            //file.name = '7eb99afb9d5f317c912f08b5212fd69a';
        },
        handleError (res, file) {
            console.log('res',res)
            console.log('file',file)

            this.$Notice.warning({
                title: 'The file format is incorrect',
                desc: `${file.errors.file.length ? file.errors.file[0] : 'Something went wrong '}`
            });
        },
        handleFormatError (res, file) {
            this.$Notice.warning({
                title: 'The file format is incorrect',
                desc: 'File format of ' + file.name + ' is incorrect, please select jpg or png.'
            });
        },
        handleMaxSize (file) {
            
        },
        
        async deleteImage(isAdd=true){
            let image
            if(!isAdd){
                this.isIconImageNew=true
                image = this.editData.iconImage
                this.editData.iconImage = ''
                this.$refs.editDatauploads.clearFiles() 
            }else{
                image = this.data.iconImage
                this.data.iconImage = ''
                this.$refs.uploads.clearFiles() 
            }
           console.log(image)
            const res = await this.callApi('post','app/delete_image', {imageName: image})
            
            if(res.status!=200){
                if(!isAdd)
                {
                this.editData.iconImage = image
                }else{
                this.data.iconImage = image
                }
                this.swr()
            }
        },

        closeEditModal(){
            this.isEditingItem = false
            this.editModal = false
        }
		/* async deleteTag(tag, i){
			if(!confirm('Are you sure you want to delete this tag?')) return
			//tag.isDeleting = true
			this.$set(tag, 'isDeleting', true)
			//$set 하면 작동 되던데 왜 안 될까..
			//작동이 안 되는 이유는 위에 변수를 선언할 때 adding같은경우는 adding자체로 했는데
			//tag에 들어있는 데이터는 선언할 때 tag.isDeleteing이라고 했다. 따로 변수를 지정하지 않았기 때문에
			//이런식으로 사용하는듯..? 신기한 사용법 처음 봄
			
						const res = await this.callApi('post', 'app/delete_tag', tag)
						
			if(res.status===200)
			{
				this.tags.splice(i,1)
				//이 부분을 왜 해주는걸까? 배열의 위치 삭제
				//i번째의 데이터를 1개 삭제한다
				//그래서 반응형으로 바로 보여줌
				//삭제된 데이터를 바로 보여줄 때
				this.s('Tag has been delete succesfully!')
			}else{
				this.swr()
			}
		}전체 주석 처리한 이유는 이렇게도 설정할 수 있는데, 모달부분을 새로 가져와서 delete modal을 만들어서 사용하는 방법을 했기에
		 */


		
	},

	async created(){
        this.token = window.Laravel.csrfToken
		const res = await this.callApi('get', 'app/get_category')
		
		if(res.status==200){
			console.log(res.data);
				this.categoryLists=res.data;
		}else{
			 this.swr()
		}
	},

	components : {
		deleteModal
	},

	computed :	{
        ...mapGetters(['getDeleteModalObj'])
	},

	watch :{
		getDeleteModalObj(obj){
			if(obj.isDeleted){
				this.categoryLists.splice(obj.deletingIndex,1)
			}
		}
	},

	/*
		async created(){
			const res = await this.callApi('post', 'app/create_tag', {tagName: 'testtag'});
			if(res.status==200){
				//console.log(res)
			}else{
				console.log(res)
				console.log('running')
			}
		}*/
	}

</script>