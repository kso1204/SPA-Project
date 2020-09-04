<template>
    <div>
        <div class="content">
			<div class="container-fluid">
				
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Tags <Button @click="addModal=true"><Icon type="md-add" /> Add Tag</Button> </p>

					<div class="_overflow _table_div">
						<table class="_table">
								<!-- TABLE TITLE -->
							<tr>
								<th>ID</th>
								<th>Tag name</th>
								<th>Created at</th>
								<th>Action</th>
							</tr>
								<!-- TABLE TITLE -->


								<!-- ITEMS -->
							<tr v-for="(tag,i) in tags" :key="i" v-if="tags.length">
								<td>{{tag.id}}</td>
								<td class="_table_name">{{tag.tagName}}</td>
								<td>{{tag.created_at}}</td>
								<td>
									<Button type="info" size="small" @click="showEditModal(tag)">Edit</Button>
    								<Button type="error" size="small" @click="showDeleteModal(tag, i)" :loading="tag.isDeleting">Delete</Button>
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
		<Input v-model="data.tagName" placeholder="Add Tag name..." />

		 <div slot="footer">
			<button type="error" @click="addModal=false">close</button>
			<button type="primary" @click="addTag" :disabled="isAdding" :loading="isAdding" >{{ isAdding? "Adding.." : "Addtag" }}</button>
		</div>
    </Modal>

<!-- tag editing modal -->

	<Modal
        v-model="editModal"
        title="Common Modal dialog box title"
		:mask-closable = "false"
		:closable = "false"
		>
		<Input v-model="editData.tagName" placeholder="Add Tag name..." />

		 <div slot="footer">
			<button type="error" @click="editModal=false">close</button>
			<button type="primary" @click="editTag" :disabled="isAdding" :loading="isAdding" >{{ isAdding? "editing.." : "Edittag" }}</button>
		</div>
    </Modal>

	<!-- tag delete modal -->

	<Modal v-model="deleteModal" width="360">
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
    </Modal>

			</div>
		</div>
    </div>
</template>

<script>

	export default {
		
	data(){
		return {
			data : {
				tagName: ''
			},
			addModal : false,
			editModal : false,
			isAdding : false,
			tags: [],
			editData :{
				tagName: ''
			},
			index: -1,
			deleteModal : false,
			deleteItem: {},
			index: -1,
			isDeleting: false,

		}
	},

	methods :{
		async addTag(){
			//if(this.data.tagName.trim()=='') return this.e('Tag name is Required')
			const res = await this.callApi('post', 'app/create_tag', this.data)
			if(res.status===201){
				this.tags.unshift(res.data);
				this.s('Tag has been added Success')
				this.addModal=false
				this.data.tagName= ''
			}else{
				if(res.status==422){
					if(res.data.errors.tagName){
						this.i(res.data.errors.tagName[0])
					}
					
				}else{
					this.swr()
				}
			}
		},
		
		async editTag(){
			const res = await this.callApi('post', 'app/edit_tag', this.editData)
			if(res.status===200){
				this.s('Tag has been edit Success')
				this.editModal=false
			}else{
				if(res.status==422){
					if(res.data.errors.tagName){
						this.i(res.data.errors.tagName[0])
					}
					
				}else{
					this.swr()
				}
			}
		},
		showEditModal(tag){
			let obj = {
				id : tag.i,
				tagName : tag.tagName
			}
			this.editData = obj
			//this.editData = tag;
			this.editModal=true
		},showDeleteModal(tag, i){
			this.deleteModal=true
			//this.$set(deleteModal, 'isDeleting', true)
			//위에는 이 부분이 안 된다.. 제대로 이해를 못한듯?
			this.deleteItem = tag
			this.index = i

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

			this.deleteModal=false
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
		const res = await this.callApi('get', 'app/get_tags')
		
		if(res.status==200){
			console.log(res.data);
				this.tags=res.data;
		}else{
			 this.swr()
		}
	}

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