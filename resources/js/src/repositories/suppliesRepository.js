import Repository from '../Repository';
import suppliesMapper from '../mappers/suppliesMappers';
const resource = '/supplies';

export default {
  get() {
    return Repository.get(`${resource}`).then(res => suppliesMapper(res.data));
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
