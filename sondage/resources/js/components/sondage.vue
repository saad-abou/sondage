<template>
    <div>
        <div class="text-center" v-if=' (user && !visible)'>
        <v-progress-circular
        :size="50"
        color="primary"
        indeterminate
        ></v-progress-circular>
        </div>
        <div v-if='user && visible'>
        <div v-if='questions.length==0'>
        <v-card class="attente">
          <v-card-text>
          <b>En attente de la question suivante ...</b>
          </v-card-text>
        </v-card>
        </div>
        <div v-if='questions.length>0'>
        <div v-if='!user.voted'>
                <v-card v-for='(question , i) of questions' :key="i" class="vote_section">
                  <p v-if='question.time && question.time!="00:00:00"'>{{displayHours}}:{{displayMinutes}}:{{displaySeconds}}</p>
                  <div v-if='question.type=="uniq"'>
                        <v-card-title>
                        <h2><b>{{question.Qst}} :</b></h2>
                        </v-card-title>
                        <v-divider></v-divider>
                        <v-card-text >
                        <v-list>
                            <v-list-item-group  color="indigo" >
                                <v-list-item v-for='(reponse , index) of question.responses' :key="index">
                                <v-list-item-content>
                                    <v-list-item-title v-text="reponse.rps" @click="vote(reponse.id)"></v-list-item-title>
                                </v-list-item-content>
                                </v-list-item>
                            </v-list-item-group>
                        </v-list>
                        </v-card-text>
                        <v-card-actions>
                         <div class='float-right'>
                        <button type="button" class="btn btn-warning" @click='notVote()'>Ignorer ce vote</button>
                         </div>
                        </v-card-actions>
                       </div>
                        <v-container v-if='question.type=="multi"' >
                          <v-card-title>
                            <h2><b>{{question.Qst}} :</b></h2>
                        </v-card-title>
                        <v-card-text >
                            <div v-for='(reponse , index) of question.responses' :key="index">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input"  v-model="selectedReps" :value="reponse"/>
                               <h4>{{reponse.rps}}</h4>
                                </label>
                            </div>
                        </v-card-text>
                        <v-card-actions>
                        <v-btn rounded color="primary" dark @click='voteSelect()'>
                           Select
                         </v-btn>
                        <div class='float-right'>
                        <button type="button" class="btn btn-warning" @click='notVote()'>Ignorer ce vote</button>
                         </div>
                        </v-card-actions>
                        </v-container>
                    </v-card>
        </div>
        <div v-if='user.voted'>
            <v-card class='stats_section'>
            <div  v-for='question of questions'>
            <h2><b>{{question.Qst}} :</b></h2>
            <div v-for='reponse of question.responses'>
                <div class='rep'>
                    <h4><b> {{reponse.rps}}</b> : {{reponse.stat.toFixed(2)}}%</h4>
                    <div class="progress">
                    <div v-if="reponse.vrai" class="progress-bar progress-bar-striped bg-success" role="progressbar" aria-valuenow="50"
                    aria-valuemin="0" v-bind:style="{ width: reponse.stat + '%' }" aria-valuemax="100" >
                    </div>
                    <div v-if="!reponse.vrai" class="progress-bar progress-bar-striped bg-danger" role="progressbar" aria-valuenow="50"
                    aria-valuemin="0" v-bind:style="{ width: reponse.stat + '%' }" aria-valuemax="100" >
                    </div>
                    </div>
                </div>
            </div>
            </div>
            </v-card>
        </div>
        </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            newdef:false,
            displayHours:0,
            displaySeconds:0,
            displayMinutes:0,
            fixDate:'',
            user:[],
            questions:[],
            stat:[],
            visible:true,
            selectedReps:[],
            timer:'',


        }
    },
    methods: {
        getData(){
            axios.get('getuser').then((res) =>{ this.user = res.data
            axios.get('getQuestionOn').then((res) =>{ this.questions = res.data
            this.questions.forEach((e)=>{
                if(e.time && !this.user.voted && e.time!="00:00:00" ){
                    this.startCount(e.time)
                }
            })
            this.countStat()
            })
            })

            this.selectedReps=[]

        },
        getDataVote(){
            axios.get('getuser').then((res) =>{ this.user = res.data
            axios.get('getQuestionOn').then((res) =>{ this.questions = res.data
            this.countStat()
            })
            })

            this.selectedReps=[]

        },
        vote(vote_id){
        this.visible=false
         axios.post('userVote').then((res)=>{
             axios.post('addVoteQuestion').then((res)=>{
            axios.post('addVoteReponse',{'id':vote_id}).then((res)=>{
                axios.get('diffuserVote').then((e)=>{this.getData()})
                })
            })
         })
        },
        voteSelect(){
            this.visible=false
         axios.post('userVote').then((res)=>{
             axios.post('addMultiVoteQuestion',{'nmb':this.selectedReps.length}).then((res)=>{
            axios.post('addMultivotes',{'select':this.selectedReps}).then((res)=>{
                axios.get('diffuserVote').then((e)=>{this.getData()})
                })
            })
         })
        },

       countStat(){
            this.questions.forEach((q)=>{
                this.visible=true
                q.responses.forEach((e)=>{
                    e.stat = (e.vote*100)/q.QstVote ;
                })
            })
        },
        getFixeDate(){
            this.fixDate ='';
            const date  = new Date();
            this.fixDate = (date.getHours() * 60 * 60 * 1000) + (date.getMinutes() * 60 * 1000) + (date.getSeconds() * 1000);

        },
        notVote(){
            this.visible=false
         axios.post('userVote').then((e)=>{
             this.getData()
         })

        },
        startCount(time){
            this.getFixeDate()
            this.timer = setInterval(()=>{
            const t = time;
            const ms = Number(t.split(':')[0]) * 60 * 60 * 1000 + Number(t.split(':')[1]) * 60 * 1000+ Number(t.split(':')[2]) * 1000;
            console.log(this.ms)
            const now  = new Date();
            const nowDate = (now.getHours() * 60 * 60 * 1000) + (now.getMinutes() * 60 * 1000) + (now.getSeconds() * 1000);
            const distance = (ms+this.fixDate) - nowDate;
            if(distance<0){
                clearInterval(this.timer);
                if(!this.user.voted){
                this.notVote()}
            }

            const hours = Math.floor(distance / this._hours);
            const minutes = Math.floor((distance % this._hours) / this._minutes);
            const seconds = Math.floor((distance % this._minutes) / this._seconds);
            this.displayHours = hours < 10 ? "0" + hours : hours ;
            this.displayMinutes = minutes < 10 ? "0" + minutes : minutes ;
            this.displaySeconds = seconds < 10 ? "0" + seconds : seconds ;


            },1000)

        }
    },
    computed: {
        _seconds:() => 1000,
        _minutes(){
            return this._seconds * 60
        },
        _hours(){
            return this._minutes * 60
        }},
    mounted() {
        this.getData()
        clearInterval(this.timer);
        window.Echo.private('diffuser')
           .listen('DiffuserEvent', (e) => {
               this.questions=[]
               this.getData()
               clearInterval(this.timer);
              });
           window.Echo.private('diffuserVote')
         .listen('DiffuserVoteEvent', (e) => {
              if(this.user.voted) {this.getDataVote()}
             });
    },
}
</script>

<style lang="css">

.vote_section {
  width: 50%;
  margin: auto;
  border-radius: 5px;
  padding-top: 50px;
  padding-right: 50px;
  padding-bottom: 50px;
  padding-left: 50px;
  box-shadow: 5px 10px 18px #000000;
  font-size:2em;
}
.question{
  position: relative;
  font-family: "Times New Roman";
  text-shadow: 5px 10px 18px #000000;
}
@media screen and (max-width: 1280px){
 .vote_section {
  width: 100%;

}
.stats_section {
  width: 100%;

}
}

.reponses{
    display: flex;
    justify-content: space-around;
    margin-top:50px;
}

.stats_section{
  width: 50%;
  margin: auto;
  border-radius: 5px;
  padding-top: 50px;
  padding-right: 50px;
  padding-bottom: 50px;
  padding-left: 50px;
  box-shadow: 5px 10px 18px #000000;
  font-family: "Times New Roman";
}

@media screen and (max-width: 1280px){
.stats_section {
  width: 100%;
}
}
.attente{
  width: 50%;
  margin: auto;
  border-radius: 5px;
  padding-top: 50px;
  padding-right: 50px;
  padding-bottom: 50px;
  padding-left: 50px;
  font-style: italic;
}
@media screen and (max-width: 1280px){
.attente {
  width: 100%;
}
}
.rep{
    margin-top:20px
}
.v-progress-circular {
  margin: 1rem;
}

</style>
