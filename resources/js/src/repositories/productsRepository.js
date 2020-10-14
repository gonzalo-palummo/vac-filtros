import Repository from '../Repository';
import productsMapper from '../mappers/productsMappers';
const resource = '/products';

export default {
  get() {
    return Repository.get(`${resource}`).then(res => productsMapper(res.data));
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
