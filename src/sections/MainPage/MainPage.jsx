import React from 'react';
import { Container } from './elements'; // Importing styled components from elements.jsx
import Hero from '../../sections/Hero/Hero';
import Cards from '../../components/Cards';

const MainPage = () => {
  return (
    <>
     
      <Container>
 <Hero />
        <Cards />
      </Container>
    </>
  );
};

export default MainPage; 