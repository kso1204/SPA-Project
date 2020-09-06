<template>
    <div>
        <div class="content">
			<div class="container-fluid">
				
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Blogs <Button @click="$router.push('/createBlog')"><Icon type="md-add" /> Create Blog</Button> </p>

					<div class="_overflow _table_div">
						<table class="_table">
								<!-- TABLE TITLE -->
							<tr>
								<th>ID</th>
								<th>Title</th>
								<th>Categories</th>
								<th>Tags</th>
								<th>Views</th>
								<th>Actions</th>
							</tr>
								<!-- TABLE TITLE -->


								<!-- ITEMS -->
							<tr v-for="(blog,i) in blogs" :key="i" v-if="blogs.length">
								<td>{{blog.id}}</td>
								<td class="_table_name">{{blog.title}}</td>
								<td ><span v-for="(c,j) in blog.cat" :key="j" v-if="blog.cat.length" ><Tag type="border">{{c.categoryName}}</Tag></span></td>
								<td ><span v-for="(t,j) in blog.tag" :key="j" v-if="blog.tag.length" ><Tag type="border">{{t.tagName}}</Tag></span></td>
								<td >{{blog.views}}</td>
								<td>
									<Button type="info" size="small" @click="showEditModal(blog)">Visit Blog</Button>
									<Button type="info" size="small" @click="$router.push(`/editBlog/${blog.id}`)">Edit</Button>
    								<Button type="error" size="small" @click="showDeleteModal(blog, i)" :loading="blog.isDeleting">Delete</Button>
								</td>
							</tr>
								<!-- ITEMS -->


						</table>
					</div>


				</div>
				 <Page :total="100" />
				
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
			isAdding : false,
			blogs: [],
			index: -1,
			//deleteModal : false,
			deleteItem: {},
			index: -1,
			isDeleting: false,

		}
	},

	methods :{
		showDeleteModal(blog, i){

			console.log(i)

			const deleteModalObj = {
				showDeleteModal : true,
				deleteUrl : 'app/delete_blog',
				data : blog,
				deletingIndex: i,
				isDeleted: false,
				msg : 'Are you sure you want to delete this blog?',
				successMsg : 'Blog has benn deleted successfully'
			}

			this.$store.commit('setDeletingModalObj', deleteModalObj)

			console.log('delete method called')

			//this.deleteModal=true
			//this.$set(deleteModal, 'isDeleting', true)
			//위에는 이 부분이 안 된다.. 제대로 이해를 못한듯?
			//this.deleteItem = tag
			//this.index = i

		}
		
	},

	async created(){
		const res = await this.callApi('get', 'app/blogsdata')
		
		if(res.status==200){
			console.log(res.data);
				this.blogs=res.data;
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
				this.blogs.splice(obj.deletingIndex,1)
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