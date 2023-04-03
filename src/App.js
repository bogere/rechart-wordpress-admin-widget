import React from 'react';
import Dashboard from './components/Dashboard';
import Chart from './components/Chart';


const App = () => {
    return (
        <div>
            <h2 className='app-title'>Upper Class Students Performance</h2>
            <Chart  />
        </div>
     );
}

export default App; 