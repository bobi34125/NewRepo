import React from 'react';
import styled from 'styled-components';
import Cards from '../../components/Cards';
import heroImage from '../../images/heroimg.png';

const HeroSection = styled.div`
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 200px; /* Adjust height as needed */
  background-image: url(${heroImage});
  background-size: cover;
  background-position: center;
  color: #fff; /* Set text color to white */
  padding: 20px; /* Add padding to ensure content does not touch the edges */
  text-align: center; /* Center align text */
`;

const HeroText = styled.h1`
  font-size: 36px;
  margin-bottom: 20px;
`;

const Hero = () => {
  return (
    <HeroSection>
      <HeroText>Welcome to our website!</HeroText>
       {/* Include Cards component here */}
    </HeroSection>
  );
};

export default Hero;