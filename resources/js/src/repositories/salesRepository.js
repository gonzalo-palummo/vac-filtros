import Repository from '../Repository';
import salesMapper from '../mappers/salesMappers';
const resource = '/sales';

export default {
  get() {
    return Repository.get(`${resource}`).then(res => salesMapper(res.data));
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
      salesMapper(res.data)
    );
  },
  getNext() {
    return Repository.get(`${resource}/next`).then(res =>
      salesMapper(res.data)
    );
  },
};
