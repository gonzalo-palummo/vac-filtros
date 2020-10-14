import Repository from '../Repository';
import clientsMapper from '../mappers/clientsMappers';
const resource = '/clients';

export default {
  get() {
    return Repository.get(`${resource}`).then(res => clientsMapper(res.data));
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
