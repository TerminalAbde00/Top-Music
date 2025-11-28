// eslint-disable-next-line no-unused-vars
import React, { useState, useEffect } from 'react';
import Axios from 'axios';
import '../App.css';
import SongCard from './components/SongCard.jsx';
import NavBar from './components/NavBar.jsx';
import BottoneLogin from './components/BottoneLogin.jsx';  
import BarraDiRicerca from './components/BarraDiRicerca.jsx'; 

function MainPage() {
  
  const [songList, setSongList] = useState([]);

  useEffect(() => {
    Axios.get("http://localhost:3002/api/get").then((response) => {
     // console.log(response.data);
      setSongList(response.data);
    });
  }, []);

  return (


    <div>
    <NavBar />
    <BarraDiRicerca />
    <BottoneLogin/>
    <div className="MainPage">
      <div className="container">
        {songList.map((song, key) => (
          <SongCard key={key} song={song} />
        ))}
      </div>
    </div>
   
  </div>
  
  );
}

export default MainPage;