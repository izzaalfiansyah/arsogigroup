import App from './App.svelte';
import 'virtual:windi.css';
import './main.css';
import '@fortawesome/fontawesome-free/css/all.min.css';

const app = new App({
	target: document.getElementById('app'),
});

export default app;
