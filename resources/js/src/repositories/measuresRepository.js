import Repository from '../Repository';
import measuresMapper from '../mappers/measuresMappers';
const resource = '/measures';

export default {
  get() {
    return Repository.get(`${resource}`).then(res => measuresMapper(res.data));
  },
  post(DTO) {
    return Repository.post(`${resource}`, DTO);
  },
  put(id, DTO) {
    return Repository.put(`${resource}/${id}`, DTO);
  },
  delete(id) {
    return Repository.delete(`${resource}/${id}`);
  },
};
