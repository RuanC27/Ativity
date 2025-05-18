import React, { useState } from 'react';
import { SafeAreaView, Text, Button, View } from 'react-native';
export default function App() {
    const [color, setColor] = useState('black');

    const toggleColor = () => {
        setColor(prevColor => (prevColor === 'black' ? 'blue' : 'black'));
    };
    return (
        <SafeAreaView style={{ flex: 1, justifyContent: 'center', alignItems: 'center', backgroundColor: '#f0f0f0' }}>
            <View style={{ marginBottom: 20 }}>
                <Text style={{ fontSize: 24, color: color }}>Texto Colorido</Text>
            </View>
            <Button title="Mudar Cor" onPress={toggleColor} />
        </SafeAreaView>
    );
}
