import React from 'react';
import { useTranslation } from 'react-i18next';
import Chart from './components/Chart';

const App = () => {
	const { t } = useTranslation();

	return (
		<div>
			<h2 className="app-title">{ t( 'upper-class-text' ) }</h2>
			<Chart />
		</div>
	);
};

export default App;
