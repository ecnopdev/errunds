<template>
  <v-container fill-height>
    <v-row>
      <v-col align="center" justify="center" class="pa-6">
        <form style="max-width:400px">
          <v-img :src="require('../assets/errunds-logo.svg')"></v-img>
          <v-text-field v-model="name" label="Username"></v-text-field>
          <v-text-field v-model="password" label="Password" type="password"></v-text-field>
          <v-combobox :items="accountTypeOptions" v-model="accountType" label="Account Type"></v-combobox>
          <v-btn class="mr-4" @click="submit" width="100%" color="primary">Submit</v-btn>
        </form>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  data: () => ({
    name: "",
    password: "",
    accountType: "Customer",
    accountTypeOptions: ["Customer","Worker"],
    select: null,
    checkbox: false
  }),
  computed: {},

  methods: {
    submit() {

      this.$store.dispatch("user/authenticate",{
          username:this.name,
          password:this.password,
          type:this.accountType,
          vm:this
      })
      .then(results => {
          console.log(results);
          if(results.data !== 'Login failed'){
              this.$store.commit('user/setActiveUser',results.data["user_array"]);
              this.$store.commit('user/setActiveRole',this.accountType);
              this.$store.commit('user/setAccessToken',results.data["token"]);
              this.$router.push("/");
          }else{
              this.$store.commit('setSnackBarText',"Invalid Credentials!");
              this.$store.commit('showSnackBar');
          }    
      })
      .catch(error => {
          console.log(error);
          this.$store.commit('setSnackBarText',"Authentication Failed!");
          this.$store.commit('showSnackBar');
      });

    },
    clear() {
      this.$v.$reset();
      this.name = "";
      this.email = "";
      this.select = null;
      this.checkbox = false;
    }
  }
};
</script>