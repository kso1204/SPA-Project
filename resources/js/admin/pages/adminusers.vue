<template>
    <div>
        <div class="content">
			<div class="container-fluid">
				
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Tags <Button @click="addModal=true"><Icon type="md-add" /> Add admin</Button> </p>

					<div class="_overflow _table_div">
						<table class="_table">
								<!-- TABLE TITLE -->
							<tr>
								<th>ID</th>
								<th>Full name</th>
								<th>email</th>
								<th>user type</th>
								<th>Created at</th>
								<th>Action</th>
							</tr>
								<!-- TABLE TITLE -->


								<!-- ITEMS -->
							<tr v-for="(user,i) in users" :key="i" v-if="users.length">
								<td>{{user.id}}</td>
								<td >{{user.fullName}}</td>
								<td >{{user.email}}</td>
								<td >{{user.role_id}}</td>
								<td>{{user.created_at}}</td>
								<td>
									<Button type="info" size="small" @click="showEditModal(user)">Edit</Button>
    								<Button type="error" size="small" @click="showDeleteModal(user, i)" :loading="user.isDeleting">Delete</Button>
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
        title="Common Modal dialog box title"
		:mask-closable = "false"
		:closable = "false"
		>
        <div class="space">
		    <Input type="text" v-model="data.fullName" placeholder="Full name..." />
        </div>
         <div class="space">
		    <Input type="text" v-model="data.email" placeholder="email" />
        </div> 
        <div class="space">
		<Input type="password" v-model="data.password" placeholder="password" />
        </div>
        <div class="space">

            <Select v-model="data.role_id"  placeholder="select admin type">
                
				<Option v-for="(r,i) in roles" :key="i" :value="r.id" :v-if="roles.length">{{r.roleName}}</Option>
                <!-- <Option value="Editor">Editor</Option> -->
                <!-- 
                <Option v-for="item in cityList" :value="item.value" :key="item.value"></Option> -->
            </Select>
        </div>

		 <div slot="footer">
			<button type="error" @click="addModal=false">close</button>
			<button type="primary" @click="addAdmin" :disabled="isAdding" :loading="isAdding" >{{ isAdding? "Adding.." : "Add User" }}</button>
		</div>
    </Modal>

<!-- tag editing modal -->

	<Modal
        v-model="editModal"
        title="Edit admin"
		:mask-closable = "false"
		:closable = "false"
		>
		 <div class="space">
		    <Input type="text" v-model="editData.fullName" placeholder="Full name..." />
        </div>
         <div class="space">
		    <Input type="text" v-model="editData.email" placeholder="email" />
        </div> 
        <div class="space">
		<Input type="password" v-model="editData.password" placeholder="password" />
        </div>
        <div class="space">
            <Select v-model="editData.userType"  placeholder="select admin type">
                <Option value="Admin">Admin</Option>
                <Option value="Editor">Editor</Option>
                <!-- 
                <Option v-for="item in cityList" :value="item.value" :key="item.value"></Option> -->
            </Select>
        </div>

		 <div slot="footer">
			<button type="error" @click="editModal=false">close</button>
			<button type="primary" @click="editAdmin" :disabled="isAdding" :loading="isAdding" >{{ isAdding? "editing.." : "Edit Admin" }}</button>
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
				fullName: '',
				email: '',
				password: '',
				role_id: null,
			},
			addModal : false,
			editModal : false,
			isAdding : false,
			users: [],
			roles:[],
			editData :{
			},
			index: -1,
			//deleteModal : false,
			deleteItem: {},
			index: -1,
			isDeleting: false,

		}
	},

	methods :{
		async addAdmin(){
            //if(this.data.tagName.trim()=='') return this.e('Tag name is Required')
            
          /*   if(this.data.fullName.trim()=='') return this.e('fullName is Required')
            if(this.data.email.trim()=='') return this.e('email is Required')
            if(this.data.password.trim()=='') return this.e('password is Required')
            if(this.data.userType.trim()=='') return this.e('user type is Required')
             */
            console.log("what the fuck")
            console.log(this.data)

			const res = await this.callApi('post', 'app/create_user', this.data)
			if(res.status===201){
				console.log(res.data)
				this.users.unshift(res.data)
				this.s('Admin has been added Success')
				this.addModal=false
				this.data= ''
			}else{
				if(res.status==422){
                    for(let i in res.data.errors){
                        
						this.e(res.data.errors[i][0])
                    }
				}else{
					this.swr()
				}
			}
		},
		
		async editAdmin(){
			const res = await this.callApi('post', 'app/edit_users', this.editData)
			if(res.status===200){
                this.users[this.index] = this.editData
				this.s('User has been edit Success')
				this.editModal=false
			}else{
				if(res.status==422){
                    for(let i in res.data.errors){
						this.e(res.data.errors[i][0])
                    }
					
				}else{
					this.swr()
				}
			}
		},
		showEditModal(user){
			this.editData = user
			this.editModal=true
		},showDeleteModal(tag, i){

			const deleteModalObj = {
				showDeleteModal : true,
				deleteUrl : 'app/delete_tag',
				data : tag,
				deletingIndex: i,
				isDeleted: false,
			}

			this.$store.commit('setDeletingModalObj', deleteModalObj)

			console.log('delete method called')

			//this.deleteModal=true
			//this.$set(deleteModal, 'isDeleting', true)
			//위에는 이 부분이 안 된다.. 제대로 이해를 못한듯?
			//this.deleteItem = tag
			//this.index = i

		},
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

			//this.deleteModal=false
			this.isDeleting =false
		},
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

		const [res,resRole] = await Promise.all([
			this.callApi('get', 'app/get_users'),
			this.callApi('get', 'app/get_roles'),
		]);
		//뭐가 더 좋을지는 알아서..?

		/* const res = await this.callApi('get', 'app/get_users')
		const resRole = await this.callApi('get', 'app/get_roles') */
		
		if(res.status==200){
			console.log(res.data);
				this.users=res.data;
		}else{
			 this.swr()
		}
		if(resRole.status==200){
			console.log(resRole.data);
				this.roles=resRole.data;
		}else{
			 this.swr()
		}
	},
	components :{
		deleteModal
	},

	computed :	{
        ...mapGetters(['getDeleteModalObj'])
	},

	watch :{
		getDeleteModalObj(obj){
			if(obj.isDeleted){
				this.tags.splice(obj.deletingIndex,1)
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