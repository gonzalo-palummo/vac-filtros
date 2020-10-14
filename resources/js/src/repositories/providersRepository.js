import Repository from '../Repository';
import providersMapper from '../mappers/providersMappers';
const resource = '/providers';

export default {
  get() {
    return Repository.get(`${resource}`).then(res => providersMapper(res.data));
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
