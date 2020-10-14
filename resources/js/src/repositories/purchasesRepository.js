import Repository from '../Repository';
import purchasesMapper from '../mappers/purchasesMappers';
const resource = '/purchases';

export default {
  get() {
    return Repository.get(`${resource}`).then(res => purchasesMapper(res.data));
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
  getLast() {
    return Repository.get(`${resource}/last`).then(res =>
      purchasesMapper(res.data)
    );
  },
  getNext() {
    return Repository.get(`${resource}/next`).then(res =>
      purchasesMapper(res.data)
    );
  },
};
