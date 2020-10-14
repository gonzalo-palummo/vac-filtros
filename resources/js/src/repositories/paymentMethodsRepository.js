import Repository from '../Repository';
import paymentMethodsMapper from '../mappers/paymentMethodsMappers';
const resource = '/payment_methods';

export default {
  get() {
    return Repository.get(`${resource}`).then(res =>
      paymentMethodsMapper(res.data)
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
