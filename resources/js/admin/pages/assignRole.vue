<template>
    <div>
        <div class="content">
			<div class="container-fluid">
				
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Role Management 

                    <Select v-model="data.id"  placeholder="select admin type" style="width:300px" @on-change="changeAdmin">
                                    
                                    <Option v-for="(r,i) in roles" :key="i" :value="r.id" :v-if="roles.length">{{r.roleName}}</Option>
                                   
                                </Select>
                                 </p>
					<div class="_overflow _table_div">
						<table class="_table">
								<!-- TABLE TITLE -->
							<tr>
								<th>Resource Name</th>
								<th>Read</th>
								<th>Write</th>
								<th>Update</th>
								<th>Delete</th>
							</tr>
								<!-- TABLE TITLE -->


								<!-- ITEMS -->
							<tr v-for="(r,i) in resources" :key="i">
								<td>{{r.resourceName}}</td>
								<td><Checkbox v-model="r.read"></Checkbox></td>
								<td><Checkbox v-model="r.write"></Checkbox></td>
								<td><Checkbox v-model="r.update"></Checkbox></td>
								<td><Checkbox v-model="r.delete"></Checkbox></td>
							</tr>
								<!-- ITEMS -->

                            <div class="cetner_button">
                                <button type="primary" :loading="isSending" :disabled="isSending" @click="assignRoles()">Assign</button>
                            </div>
						</table>
					</div>


				</div>
				 <Page :total="100" />
				

       
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
				roleName: '',
				id: null,
            },

            isSending : false,

            roles:[],

            resources:[
                {
                    resourceName : 'tags', 
                    read: false,
                    write: false,
                    update: false,
                    delete: false, 
                    name: 'tags', 
                },
                
                {
                    resourceName : 'category', 
                    read: false,
                    write: false,
                    update: false,
                    delete: false, 
                    name: 'category', 
                },
                
                {
                    resourceName : 'adminusers', 
                    read: false,
                    write: false,
                    update: false,
                    delete: false, 
                    name: 'adminusers', 
                },
                
                {
                    resourceName : 'role', 
                    read: false,
                    write: false,
                    update: false,
                    delete: false, 
                    name: 'role', 
                },
                
                {
                    resourceName : 'assignRole', 
                    read: false,
                    write: false,
                    update: false,
                    delete: false, 
                    name: 'assignRole', 
                },

                
                {
                    resourceName : 'home', 
                    read: false,
                    write: false,
                    update: false,
                    delete: false, 
                    name: '/', 
                },

            ],

            defaultResourcesPermission:[
                {
                    resourceName : 'tags', 
                    read: false,
                    write: false,
                    update: false,
                    delete: false, 
                    name: 'tags', 
                },
                
                {
                    resourceName : 'category', 
                    read: false,
                    write: false,
                    update: false,
                    delete: false, 
                    name: 'category', 
                },
                
                {
                    resourceName : 'adminusers', 
                    read: false,
                    write: false,
                    update: false,
                    delete: false, 
                    name: 'adminusers', 
                },
                
                {
                    resourceName : 'role', 
                    read: false,
                    write: false,
                    update: false,
                    delete: false, 
                    name: 'role', 
                },
                
                {
                    resourceName : 'assignRole', 
                    read: false,
                    write: false,
                    update: false,
                    delete: false, 
                    name: 'assignRole', 
                },

                
                {
                    resourceName : 'home', 
                    read: false,
                    write: false,
                    update: false,
                    delete: false, 
                    name: '/', 
                },

            ],
			
		}
	},

	methods :{
		async assignRoles(){
            let data = JSON.stringify(this.resources)
            const res = await this.callApi('post','app/assign_roles',{'permission' :data, 'id' : this.data.id })
            if(res.status==200){
                this.s('Role has been assigned successfully.')
                let index = this.roles.findIndex(role => role.id == this.data.id)
                this.roles[index].permission = data
            }else{
                this.swr()
            }
            console.log(this.resources);
        },

        changeAdmin(){

            let index = this.roles.findIndex(role => role.id == this.data.id)
            let permission = this.roles[index].permission
            if(!permission){
                this.resources = this.defaultResourcesPermission
            }else{
                 this.resources = JSON.parse(permission)
            }
        },
		

		
	},


        async created(){
            console.log(this.$route)
            const res = await this.callApi('get', 'app/get_roles')
            
            if(res.status==200){
                    this.roles=res.data;
                    if(res.data.length){
                        this.data.id = res.data[0].id;
                        if(res.data[0].permission){
                            this.resources = JSON.parse(res.data[0].permission)
                        }
                    }
            }else{
                this.swr()
            }
        },
    }

</script>