// eslint-disable-next-line no-unused-vars
import React from 'react';
import { useNavigate } from 'react-router-dom'; // Importa useNavigate
import './css/BottoneLogin.css';

const BottoneLogin = () => {
  const navigate = useNavigate(); // Ottieni la funzione di navigazione

  const handleBackClick = () => {
    navigate('/LoginPage'); // Reindirizza alla pagina principale
  };

  return (
    <button className="bottoneLogin" type="button" onClick={handleBackClick}>
      <img 
        src="/iconaLogin.svg" 
        alt="iconaLogin" 
      />
    </button>
  );
};

export default BottoneLogin;