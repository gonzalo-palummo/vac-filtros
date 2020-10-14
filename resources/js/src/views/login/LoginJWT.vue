<template>
  <div class="my-5">
    <vs-input
      v-validate="'required|email'"
      data-vv-validate-on="blur"
      name="correo"
      icon-no-border
      icon="icon icon-user"
      icon-pack="feather"
      label-placeholder="Correo"
      v-model="email"
      class="w-full pt-5"
    />
    <span class="text-danger text-sm">{{ errors.first('correo') }}</span>

    <vs-input
      data-vv-validate-on="blur"
      v-validate="'required'"
      type="password"
      name="contraseña"
      icon-no-border
      icon="icon icon-lock"
      icon-pack="feather"
      label-placeholder="Contraseña"
      v-model="password"
      class="w-full pt-5"
    />
    <span class="text-danger text-sm">{{ errors.first('contraseña') }}</span>

    <div class="flex flex-wrap justify-between my-5">
      <router-link to="/forgot-password">¿Olvidaste la contraseña?</router-link>
    </div>
    <div class="flex flex-wrap justify-between mb-3">
      <!--<vs-button type="border" @click="registerUser">Registrarse</vs-button>-->
      <vs-button class="my-5" :disabled="!validateForm" @click="login"
        >Ingresar</vs-button
      >
    </div>
  </div>
</template>

<script>
import { RepositoryFactory } from './../../repositories/RepositoryFactory';
const AuthRepository = RepositoryFactory.get('auth');
export default {
  data() {
    return {
      email: '',
      password: '',
    };
  },
  computed: {
    validateForm() {
      return !this.errors.any() && this.email !== '' && this.password !== '';
    },
  },
  methods: {
    /*checkLogin() {
      if (this.$store.state.auth.isUserLoggedIn()) {

        this.$vs.notify({
          title: 'Login Attempt',
          text: 'You are already logged in!',
          iconPack: 'feather',
          icon: 'icon-alert-circle',
          color: 'warning',
        });

      }
    },*/
    async login() {
      this.$vs.loading();
      let response = undefined;
      const DTO = {
        email: this.email,
        password: this.password,
      };
      try {
        response = await AuthRepository.post(DTO);
      } catch (err) {
        response = false;
        if (err.response.data.message === 'Incorrect email and password') {
          this.$eventBus.$emit('show-message', {
            color: 'warning',
            text: 'Las credenciales son incorrectas',
          });
        }
      }
      if (response !== false) {
        this.$store.dispatch('updateUserInfo', {
          displayName: 'VAC Filtros',
          userRole: 'admin',
          token: response.data.access_token,
        });
        this.$acl.change('admin');
        this.$router.push('/').catch(() => {});
      }
      this.$vs.loading.close();
    },
    registerUser() {
      this.$router.push('/register').catch(() => {});
    },
  },
  created() {
    this.$store.dispatch('updateUserInfo', {
      displayName: null,
      token: null,
      userRole: 'anonymous',
    });
    this.$acl.change('anonymous');
  },
};
</script>
