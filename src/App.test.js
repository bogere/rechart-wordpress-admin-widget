import React from 'react'
import {render} from '@testing-library/react'
import App from './App'
import Chart from './components/Chart'
 
 it('should take a snapshot of the App', () => {
    const { asFragment } = render(<App />)
    
    expect(asFragment(<App />)).toMatchSnapshot()

})


it('should take a snapshot of the Chart', () => {
    const { asFragment } = render(<Chart />)
    
    expect(asFragment(<Chart />)).toMatchSnapshot()

})