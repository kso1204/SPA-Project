<template>
  <div class="content">
			<div class="container-fluid">
        <h1>I will show how all other component react to changes</h1>
        The master Component : {{ counter }} {{ counter2 }}
        <div>
            <comA></comA>
        </div>
         <div>
            <comB></comB>
        </div>
         <div>
            <comC></comC>
        </div>


        <button type="info" @click="changeCounter">
            change the state of the counter
        </button>

			</div>
            
            </div>
</template>

<script>
import comA from './comA'
import comB from './comB'
import comC from './comC'
import {mapGetters, mapActions, mapMutations} from 'vuex'

    export default {
        data(){
            return {
                localVar : 'some value'
            }
        },
        mehotds :{
            ...mapActions({
                
            })
        },
        computed : {
            /*  */
            /*...mapGetters(['getCounter'])  */
            ...mapGetters({
                'counter' : 'getCounter',
                'counter2' : 'getCounterByHalf'
            })
            /* 앞에 선언한건 여기서 쓸 변수의 이름
                뒤에 선언한건 store.js에서 선언한 getter의 함수이름..?
                그 함수이름을 가져온다고 할 때 mapGetters라는 변수를 쓰고
                이 변수 이름을 통해서 값을 사용하는듯..? 좀 복잡하네
            */
            
        },
        created(){
            console.log(this.$store.state.counter)
        },
        methods : {
            changeCounter(){
                this.$store.dispatch('changeCounterAction',1)
                //this.$store.commit('chnageTheCounter', 1)
            },
            runSomeThingWhenCoutnerChange(){
                console.log ('i am baseed on each changes hanpp')
            }
        },
        components : {
            comA,
            comB,
            comC,
        },
        watch : {
            counter(value){ //위에 선언한 getter의 네임을 가져옴
                console.log('counter is changing', value)
                this.runSomeThingWhenCoutnerChange()
                console.log('local var',this.localVar)

            }
        }
    }
</script>