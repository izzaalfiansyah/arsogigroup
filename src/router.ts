import type { RouteDefinition } from 'svelte-spa-router';
import Dashboard from './pages/Dashboard.svelte';
import User from './pages/User.svelte';
import Pelanggan from './pages/Pelanggan.svelte';
import Produk from './pages/Produk.svelte';
import Profil from './pages/Profil.svelte';
import PelangganSave from './pages/PelangganSave.svelte';
import TakeOrder from './pages/TakeOrder.svelte';
import PurchaseOrder from './pages/PurchaseOrder.svelte';
import Penjualan from './pages/Penjualan.svelte';
import Laporan from './pages/Laporan.svelte';
import TakeOrderSave from './pages/TakeOrderSave.svelte';
import PurchaseOrderSave from './pages/PurchaseOrderSave.svelte';
import PenjualanSave from './pages/PenjualanSave.svelte';
import PenjualanDetail from './pages/PenjualanDetail.svelte';

export const routes: RouteDefinition = {
	'/': Dashboard,
	'/user': User,
	'/pelanggan': Pelanggan,
	'/pelanggan/create': PelangganSave,
	'/pelanggan/:id': PelangganSave,
	'/produk': Produk,
	'/profil': Profil,
	'/take-order': TakeOrder,
	'/take-order/create': TakeOrderSave,
	'/take-order/:id': TakeOrderSave,
	'/purchase-order': PurchaseOrder,
	'/purchase-order/create': PurchaseOrderSave,
	'/purchase-order/:id': PurchaseOrderSave,
	'/penjualan': Penjualan,
	'/penjualan/create': PenjualanSave,
	'/penjualan/:id': PenjualanSave,
	'/penjualan/:id/detail': PenjualanDetail,
	'/laporan': Laporan,
};
