<template>
  <div class="h-screen flex w-full bg-img">
    <div class="vx-col w-4/5 sm:w-4/5 md:w-3/5 lg:w-3/4 xl:w-3/5 mx-auto self-center">
      <vx-card>
        <div slot="no-body" class="full-page-bg-color">
          <div class="vx-row">
            <div class="vx-col hidden sm:hidden md:hidden lg:block lg:w-1/2 mx-auto self-center">
              <img src="@assets/images/pages/forgot-password.png" alt="login" class="mx-auto" />
            </div>
            <div class="vx-col sm:w-full md:w-full lg:w-1/2 mx-auto self-center d-theme-dark-bg">
              <div class="p-8">
                <div class="vx-card__title mb-8">
                  <h4 class="mb-4">Recupera tu contraseña</h4>
                  <p>
                    Por favor ingresa tu dirección de correo electrónico y te
                    enviaremos las instrucciones para restablecer tu contraseña.
                  </p>
                </div>

                <vs-input
                  type="email"
                  label-placeholder="Correo"
                  v-model="email"
                  class="w-full mb-8"
                />
                <vs-button type="border" to="/login" class="px-4 w-full md:w-auto">Volver al login</vs-button>
                <vs-button
                  class="float-right px-4 w-full md:w-auto mt-3 mb-8 md:mt-0 md:mb-0"
                  :disabled="!email"
                  @click="sendEmail"
                >Recuperar contraseña</vs-button>
              </div>
            </div>
          </div>
        </div>
      </vx-card>
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
    };
  },
  methods: {
    async sendEmail() {
      let response = undefined;
      try {
        response = await AuthRepository.recoverPassword(this.email);
      } catch (err) {
        response = false;
      }
      if (response !== false) {
        this.$eventBus.$emit('show-message', {
          color: 'success',
          text:
            'Se te ha enviado la contraseña al correo electrónico. Revisa la casilla de spam',
        });
      }
    },
  },
};
</script>
