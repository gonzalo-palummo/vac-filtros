import Repository from '../Repository';
import manufacturingMapper from '../mappers/manufacturingMappers';
const resource = '/productions';

export default {
  get() {
    return Repository.get(`${resource}`).then(res =>
      manufacturingMapper(res.data)
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
