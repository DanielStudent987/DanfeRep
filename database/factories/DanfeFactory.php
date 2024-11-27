<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Danfe>
 */
class DanfeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'chave' => $this->faker->unique()->numerify(str_repeat('#', 44)),
            'inserido_por' => User::all()->random()->id,
            'content_xml' => $this->generateFakeXml(),
        ];
    }

   /**
     * Generate a fake XML content.
     *
     * @return string
     */
    private function generateFakeXml(): string
    {
        $xml = new \SimpleXMLElement('<danfe/>');
        $xml->addChild('chaveAcesso', $this->faker->unique()->numerify(str_repeat('#', 44)));
        $xml->addChild('emitente', $this->faker->company);
        $xml->addChild('destinatario', $this->faker->name);
        $xml->addChild('valorTotal', $this->faker->randomFloat(2, 100, 1000));

        return $xml->asXML();
    }
}
