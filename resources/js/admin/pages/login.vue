<template>
    <div>
        <h1>this is login</h1>
        
        <div class="content">
                
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20 col-md-4">
                    <div class="login_header">
                        <h1>login to the dashboard</h1>
                    </div>
                            <div class="space">
                        <Input type="text" v-model="data.email" placeholder="Full name..." />
                    </div>
                    <div class="space">
                        <Input type="password" v-model="data.password"  placeholder="******" />
                    </div> 
                    <div class="login_footer">
                        <button type="primary" @click="login" :disabled="isLogging" :loading="isLogging">{{isLogging ? 'Logining' : 'Login'}}</button>
                    </div>
				</div>
                
                </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                data :{
                    email :  '',
                    password: ''
                },
                isLogging:false,
            }
        },

        methods: {
            async login(){
                    
                if(this.data.email.trim()=='') return this.e('email is Required')
                if(this.data.password.trim()=='') return this.e('password is Required')
                if(this.data.password.length <5 ) return this.e('Please check your password')
                this.isLogging = true
                const res = await this.callApi('post', 'app/admin_login', this.data)

                if(res.status===200){
                    this.s('login')
                    window.location ='/'
                   // this.s(res.data.msg)
                }else{
                    if(res.status===401){
                        this.i(res.data.msg)
                    }else if(res.status===422){
                        for(let i in res.data.errors){
                            this.e(res.data.errors[i][0])
                        }
                    }else{
                        this.swr()
                    }
                }
                this.isLogging=false
            }
        }
    }
    
</script>

<style scoped>
    ._1adminOverveiw_table_recent{
        margin: 0 auto;
    }
    .login_footer{
        text-align : center;
    }
</style>