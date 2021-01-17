<template>
    <div class="container">

    </div>
</template>

<script>

    export default {
        data(){
          return{
              userId:this.$userId,
              budgetId:null,
              error:null,
              loading:false,
              budgets:[],
              transactions:[]
          }
        },
        created(){
          if(this.userId){
              if(!this.budgetId){
                  this.getBudget();
              }else{
                  this.selectBudget(this.budgetId);
              }
          }
        },
        mounted() {
            console.log('Component mounted.')
        },
        methods:{
            findYNABToken() {
                let token = null;
                const search = window.location.hash.substring(1).replace(/&/g, '","').replace(/=/g,'":"');
                if (search && search !== '') {
                    // Try to get access_token from the hash returned by OAuth
                    const params = JSON.parse('{"' + search + '"}', function(key, value) {
                        return key === '' ? value : decodeURIComponent(value);
                    });
                    token = params.access_token;
                    sessionStorage.setItem('ynab_access_token', token);
                    window.location.hash = '';
                } else {
                    // Otherwise try sessionStorage
                    token = sessionStorage.getItem('ynab_access_token');
                }
                return token;
            },
            getBudget(){
                this.loading = true;
                this.error = null;
                this.ynab.api.budgets().getBudgets().then((res)=>{
                    this.budgets = res.data.budgets;
                })
                .catch((err)=>{
                    this.error = err;
                })
                .finally(()=>{
                    this.loading = false;
                })
            },
            selectBudget(id){
                this.loading = true;
                this.error = null;
                this.budgetId = id;
                this.transcations = [];
                this.ynab.api.budgets().getTransactions(id).then((res)=>{
                    this.transactions = res.data.transactions;
                })
                .catch((err)=>{
                    this.error = err;
                })
                .finally(()=>{
                    this.loading = false;
                })
            },
            authorizeWithYNAB(e){
                e.preventDefault();
                const uri = `https://app.youneedabudget.com/oauth/authorize?client_id=${this.ynab.clientId}&redirect_uri=${this.ynab.redirectUri}&response_type=token`;
                location.replace(uri);
            },
            resetToken(){
                sessionStorage.removeItem('ynab_access_token');
                this.ynab.token = null;
                this.error = null;
            }
        }
    }
</script>
