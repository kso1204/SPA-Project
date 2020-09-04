<template>
    <div>   

        <h1>this number is : {{ views }}</h1>
        <button @click="updateCounter(1)"> Increase</button>
        <button @click="updateCounter(-1)"> Decrease</button>
        <br>
        <br>
        <br>

        <div v-for="(blog, i) in blogs" :key="i" v-if="blogs.length"> 
                <h1>{{blog.title}}</h1>
                <p>{{blog.post}}</p>
                <p>{{blog.id}}</p>
        </div>

        <br>
        <br>
        <br>

        <div>
            <h1 v-if="showItem">show this item if "showItem" is true</h1>
            <h1 v-else>show this item if "showItem" is false</h1>
        </div>
        <!-- 다른 div에서는 else가 먹히지 않는다는 내용
         <div>
            <h1 v-else>show this item if "showItem" is false</h1>
        </div>-->

        <button @click="showItem=!showItem">Change showItem</button>
    </div>

</template>

<script>
    export default {
        data(){
            return {
                views : 0,
                blogs : [],
                showItem : false,
            }
        },
        methods:{
            updateCounter(number){
                this.views += number
            },
            changeShowItem(){
                this.showItem = !this.showItem
            }
        },

        created() {
            // call the server to get views of this blog post..
            this.views = 100
            // get the blog posts

            let posts = [
                    {title: 'this is blog title', post : 'this is the blog post1', id:1 },
                    {title: 'this is blog title 1', post : 'this is the blog post2', id:2 },
                    {title: 'this is blog title 2', post : 'this is the blog post3', id:3 },
                    {title: 'this is blog title 3', post : 'this is the blog post4', id:4 },]
            
            this.blogs = posts
        },
    }
</script>