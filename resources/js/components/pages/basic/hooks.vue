<template>
    <div>
        <h1 ref="myref">Hook</h1>
        <h2>My name is: {{ name }}</h2>
    </div>
</template>

<script>
    export default{
        data(){
            return {
                name: 'sadek'
            }
        },

        beforeCreate() {

            //이건 data보다도 전에 사용된다고
            console.log(this.name)
        },

        created(){
            // call mosly initial data from it...
            // 이게 먼저 실행된다고 h1불러오기 전에
            console.log('data and other functio nis available but not html', this.name);
            //근데 data에 있는 애들을 그냥 가져올 수 있다고

            console.log(this.$refs.myref);
        },

        mounted() {
            //이거는 h1이 실행되고 나서의 데이터를 가져온다는 느낌으로..? 잘 안들림 ㅠㅠ
            console.log(this.$refs.myref)
        },
    }
</script>