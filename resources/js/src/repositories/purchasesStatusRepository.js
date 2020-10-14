import Repository from '../Repository';
import purchasesStatusMapper from '../mappers/purchasesStatusMappers';
const resource = '/purchases_status';

export default {
  get() {
    return Repository.get(`${resource}`).then(res =>
      purchasesStatusMapper(res.data)
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
