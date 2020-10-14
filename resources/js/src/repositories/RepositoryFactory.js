import PurchasesRepository from './purchasesRepository';
import ProvidersRepository from './providersRepository';
import PaymentMethodsRepository from './paymentMethodsRepository';
import SuppliesRepository from './suppliesRepository';
import PurchasesStatusRepository from './purchasesStatusRepository';
import SalesRepository from './salesRepository';
import ClientsRepository from './clientsRepository';
import ProductsRepository from './productsRepository';
import SalesStatusRepository from './salesStatusRepository';
import MeasuresRepository from './measuresRepository';
import AuthRepository from './authRepository';
import CategoriesRepository from './categoriesRepository';
import ManufacturingRepository from './manufacturingRepository';

const repositories = {
  auth: AuthRepository,
  purchases: PurchasesRepository,
  providers: ProvidersRepository,
  paymentMethods: PaymentMethodsRepository,
  supplies: SuppliesRepository,
  purchasesStatus: PurchasesStatusRepository,
  sales: SalesRepository,
  clients: ClientsRepository,
  products: ProductsRepository,
  salesStatus: SalesStatusRepository,
  categories: CategoriesRepository,
  measures: MeasuresRepository,
  manufacturing: ManufacturingRepository,
};

export const RepositoryFactory = {
  get: name => repositories[name],
};
