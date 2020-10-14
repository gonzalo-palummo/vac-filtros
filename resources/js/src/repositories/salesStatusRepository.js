import Repository from '../Repository';
import salesStatusMapper from '../mappers/salesStatusMappers';
const resource = '/sales_status';

export default {
  get() {
    return Repository.get(`${resource}`).then(res =>
      salesStatusMapper(res.data)
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
