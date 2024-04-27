// components/Hero/hero.jsx

import styled from 'styled-components';
import heroImage from '../../images/Hero.jpg';

// Define styled components for the Hero section
const HeroContainer = styled.div`
  /* Set background image */
    background-image: url(${heroImage}); // Use the imported image variable here

  /* Set background size to cover the entire container */
  background-size: cover;
  /* Set background position to center */
  background-position: center;
  /* Specify height of the Hero section */
  height: 40vh;
  width: 100%;
  text-align: center; /* Adjust as needed */
  /* Add other styles as needed */
color:white;
`;

const HeroContent = styled.div`
  /* Your styles for the hero content */
`;

// Export the styled components
export { HeroContainer, HeroContent };