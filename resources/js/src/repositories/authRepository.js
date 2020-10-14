import Repository from '../Repository';
const resource = '/auth';

export default {
  post(DTO) {
    return Repository.post(`${resource}/login`, DTO);
  },
  recoverPassword(email) {
    return Repository.get(`${resource}/recover/${email}`);
  },
};
