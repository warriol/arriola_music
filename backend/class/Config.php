<?php

namespace class;

use PDO;
use PDOException;

/**
 * Clase Abstracta Config
 * Gestiona la carga de variables .env y la conexión PDO.
 */
abstract class Config
{
    protected readonly string $key;
    protected readonly string $secretKey;

    private $debug = true;

    private $host;
    private $user;
    private $pass;
    private $db;

    private $conexion;

    private $rutaEnv = "../.env";

    public function __construct()
    {
        $this->key = "josearriola";

        $this->cargarVariables();
        $this->conectar();
    }

    private function cargarVariables(): void
    {
        $filePath = $this->rutaEnv;
        if (file_exists($filePath)) {
            $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos(trim($line), '#') === 0) {
                    continue;
                }
                list($name, $value) = explode('=', $line, 2);
                $this->setEnvVariable(trim($name), trim($value));
            }
        } else {
            throw new Exception(".env no encontrado.");
        }
    }

    private function setEnvVariable($name, $value): void
    {
        switch ($name) {
            case 'DB_HOST':
                $this->DB_Host = $this->desencriptarTexto($value);
                break;
            case 'DB_USER':
                $this->DB_User = $this->desencriptarTexto($value);
                break;
            case 'DB_PASS':
                $this->DB_Pass = $this->desencriptarTexto($value);
                break;
            case 'DB_NAME':
                $this->DB_Name = $this->desencriptarTexto($value);
                break;
            case 'SECRET_KEY':
                $this->secretKey = $this->desencriptarTexto($value);
                break;
        }
    }

    private function desencriptarTexto($textoEncriptado): string
    {
        $datos = base64_decode($textoEncriptado);
        $iv = substr($datos, 0, openssl_cipher_iv_length('aes-256-cbc'));
        $textoEncriptado = substr($datos, openssl_cipher_iv_length('aes-256-cbc'));
        return openssl_decrypt($textoEncriptado, 'aes-256-cbc', $this->key, 0, $iv);
    }

    private function conectar(): void
    {
        try {
            $this->conexion = new PDO("mysql:host=$this->host;dbname=$this->db", $this->user, $this->pass);
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conexion->exec("SET CHARACTER SET utf8");
        } catch (PDOException $e) {
            throw new Exception("Error al conectar a la base de datos: " . $e->getMessage());
        }
    }

    public function getConexion(): PDO
    {
        return $this->conexion;
    }

    public function estaAutenticado(): void
    {
        $headers = apache_request_headers();
        if (!isset($headers['Authorization'])) {
            http_response_code(401);
            echo json_encode(["message" => "Unauthorized"]);
            exit;
        }

        $authToken = $headers['Authorization'];
        $validToken = $this->secretKey;

        if ($authToken !== $validToken) {
            http_response_code(401);
            echo json_encode(["message" => "Unauthorized"]);
            exit;
        }
    }

    public function debug($var, $val = '-'): void
    {
        if ($this->debug) {
            $file = fopen("../debug.log", "a") or die("Error creando archivo");
            $texto = '[' . date("Y-m-d H:i:s") . ']::[' . $var . ']:-> [' . $val . ']';
            fwrite($file, $texto . PHP_EOL) or die("Error escribiendo en el archivo");
            fclose($file);
        }
    }
}