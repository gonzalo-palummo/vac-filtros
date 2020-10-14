import Vue from 'vue';
import { AclInstaller, AclCreate, AclRule } from 'vue-acl';
import router from '@/router';

Vue.use(AclInstaller);

const userInfo = JSON.parse(localStorage.getItem('userInfo'));
const initialRole =
  userInfo && userInfo.userRole ? userInfo.userRole : 'anonymous';

export default new AclCreate({
  initial: initialRole,
  notfound: '/pages/unauthorized',
  router,
  acceptLocalRules: true,
  globalRules: {
    isAdmin: new AclRule('admin').generate(),
    isEditor: new AclRule('editor').or('admin').generate(),
    isAnonymous: new AclRule('anonymous').generate(),
    isPublic: new AclRule('anonymous')
      .or('editor')
      .or('admin')
      .generate(),
  },
});
