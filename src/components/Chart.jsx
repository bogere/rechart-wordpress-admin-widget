import React, {useState, useEffect} from "react"
import {
    BarChart,
    Bar,
    XAxis,
    YAxis,
    CartesianGrid,
    Tooltip,
    Legend,
    ResponsiveContainer,
  } from "recharts";

  const inputDayList = [
     {label: '3 days', value: 3},
     {label: '15 days', value: 15},
     {label: 'Last Month', value: 30}
  ]
  
  export default function Chart() {

    const [graphData, setGraphData ] = useState()
    const [selectedDay, setSelectedDay] = useState()

    useEffect(()=>{
         fetchDatas(12) //Last 12 days
    },[])

    const fetchDatas = async (inputDay) => {

        //const res = await fetch(`/wp-json/myrest/v1/project/${inputDay}`)
        //const postUrl = `/wp-json/myrest/v1/project`
        const formData = {
          'number': 12,
          'period': 'days'
        }
        // const formData = new FormData({
        //   'number': 12,
        //   'period' : 'days'
        // })
        const options = {
          method: 'POST',
          headers: {
          'Content-Type': 'application/json',
          },
          body: JSON.stringify(formData)
          };
         const res = await fetch(postUrl,options)
        const data = await res.json();
        console.log('performance graph',data);
        if (data.data.message) {
            setGraphData(data.data.message)
        }
      }

    const updateSelectedInput = (value)=>{
        setSelectedDay(value)
        fetchDatas(value)

    }

    return (
      <React.Fragment>
        <h3>Hello pipo</h3>
         <select 
             value={selectedDay}
             onChange={evt => updateSelectedInput(evt.target.value)}
        >
          {
            inputDayList.map(k=>{
                return(
                    <option value = {k.value}>{k.label}</option>
                )
            })
          }
         </select><br></br>
      <ResponsiveContainer width="100%" height={400}>
        <BarChart
          data = {graphData}
          margin={{
            top: 5,
            right: 30,
            left: 20,
            bottom: 5,
          }}
        >
          <CartesianGrid strokeDasharray="3 3" />
          <XAxis dataKey="name" />
          <YAxis />
          <Tooltip />
          <Legend />
          <Bar dataKey="pv" fill="#8884d8" />
          <Bar dataKey="uv" fill="#82ca9d" />
        </BarChart>
      </ResponsiveContainer>
      </React.Fragment>
    );
  }