import Repository from '../Repository';
import categoriesMapper from '../mappers/categoriesMappers';
const resource = '/categories';

export default {
  get() {
    return Repository.get(`${resource}`).then(res =>
      categoriesMapper(res.data)
    );
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
