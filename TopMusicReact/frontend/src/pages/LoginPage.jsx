import React, { useState } from 'react';
import Axios from 'axios';
import './LoginPage.css';
import BottoneBack from './components/BottoneBack';

const LoginPage = () => {
  const [isLoginVisible, setIsLoginVisible] = useState(true);
  const [formData, setFormData] = useState({ username: '', email: '', password: '', confirmPassword: '' });
  const [errors, setErrors] = useState({ usernameExists: false, emailExists: false, registrationError: '', loginError: '' });

  const resetForm = () => {
    setFormData({ username: '', email: '', password: '', confirmPassword: '' });
    setErrors({ usernameExists: false, emailExists: false, registrationError: '', loginError: '' });
  };

  const toggleForm = (e) => {
    e.preventDefault();
    setIsLoginVisible(!isLoginVisible);
    resetForm();
  };

  const handleInputChange = (e) => {
    const { name, value } = e.target;
    setFormData({ ...formData, [name]: value });
  };

  const validateFields = (fields) => {
    for (const [key, value] of Object.entries(fields)) {
      if (!value) return `Il campo ${key} è obbligatorio`;
    }
    return null;
  };

  const handleApiError = (error, type) => {
    console.error(`Errore durante il ${type}:`, error);
    setErrors({ ...errors, [`${type}Error`]: error.response?.data?.error || `Errore durante il ${type}` });
    setTimeout(() => setErrors({ ...errors, [`${type}Error`]: '' }), 4000);
  };

  const handleLogin = async (e) => {
    e.preventDefault();
    const validationError = validateFields({ username: formData.username, password: formData.password });
    if (validationError) return setErrors({ ...errors, loginError: validationError });

    try {
      const response = await Axios.post('http://localhost:3002/api/login', { username: formData.username, password: formData.password });
      if (response.data.success) window.location.href = "/";
      else setErrors({ ...errors, loginError: 'Credenziali non valide' });
    } catch (error) {
      handleApiError(error, 'login');
    }
  };

  const handleRegister = async (e) => {
    e.preventDefault();
    const validationError = validateFields(formData);
    if (validationError) return setErrors({ ...errors, registrationError: validationError });

    if (formData.password !== formData.confirmPassword) {
      return setErrors({ ...errors, registrationError: 'Le password non coincidono' });
    }

    if (formData.password.length < 6) {
      return setErrors({ ...errors, registrationError: 'La password deve essere di almeno 6 caratteri' });
    }

    try {
      const checkResponse = await Axios.post('http://localhost:3002/api/check_username', { username: formData.username, email: formData.email });
      if (checkResponse.data.usernameExists || checkResponse.data.emailExists) {
        setErrors({ ...errors, usernameExists: checkResponse.data.usernameExists, emailExists: checkResponse.data.emailExists });
        setTimeout(() => setErrors({ ...errors, usernameExists: false, emailExists: false }), 4000);
        return;
      }

      const registerResponse = await Axios.post('http://localhost:3002/api/register', { username: formData.username, password: formData.password, name: formData.username, email: formData.email });
      if (registerResponse.data.success) window.location.href = "/upload";
      else setErrors({ ...errors, registrationError: 'Errore durante la registrazione' });
    } catch (error) {
      handleApiError(error, 'registration');
    }
  };

  return (
    <>
      <BottoneBack />
      <section className="containerLogin">
        <div className="login-container">
          <div className="circle circle-one"></div>
          <div className="form-container">
            <img src={'/illustration.png'} alt="illustration" className="illustration" />
            <h1 className="opacity" id="form-title">{isLoginVisible ? 'LOGIN' : 'REGISTER'}</h1>
            <form onSubmit={isLoginVisible ? handleLogin : handleRegister}>
              <input type="text" name="username" placeholder="USERNAME" value={formData.username} onChange={handleInputChange} required />
              {!isLoginVisible && (
                <>
                  {errors.usernameExists && <p className="error">Username già esistente</p>}
                  <input type="email" name="email" placeholder="EMAIL" value={formData.email} onChange={handleInputChange} required />
                  {errors.emailExists && <p className="error">Email già esistente</p>}
                </>
              )}
              <input type="password" name="password" placeholder="PASSWORD" value={formData.password} onChange={handleInputChange} required />
              {!isLoginVisible && <input type="password" name="confirmPassword" placeholder="CONFIRM PASSWORD" value={formData.confirmPassword} onChange={handleInputChange} required />}
              {errors.loginError && <p className="error">{errors.loginError}</p>}
              {errors.registrationError && <p className="error">{errors.registrationError}</p>}
              <button type="submit" className="opacity">{isLoginVisible ? 'ACCEDI' : 'REGISTRATI'}</button>
            </form>
            <div className="register-forget opacity">
              <a href="#" onClick={toggleForm}>{isLoginVisible ? 'REGISTER' : 'LOGIN'}</a>
              <a href=""><i className="fas fa-key"></i> FORGOT PASSWORD</a>
            </div>
          </div>
          <div className="circle circle-two"></div>
        </div>
      </section>
    </>
  );
};

export default LoginPage;