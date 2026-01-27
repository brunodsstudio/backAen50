<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class GenerateTestToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'token:generate-test 
                            {--email=test@aeranerd.com : Email do usuário}
                            {--password=password123 : Senha do usuário}
                            {--name=Test User : Nome do usuário}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gera um token JWT Bearer para teste no Swagger';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->option('email');
        $password = $this->option('password');
        $name = $this->option('name');

        // Verifica se o usuário já existe
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->info("Criando novo usuário: $email");
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
            ]);
            $this->info("✓ Usuário criado com sucesso!");
        } else {
            $this->info("Usuário já existe: $email");
            // Atualiza a senha para garantir que seja a especificada
            $user->password = Hash::make($password);
            $user->save();
            $this->info("✓ Senha atualizada!");
        }

        // Gera o token JWT
        $token = Auth::guard('api')->login($user);

        if ($token) {
            $this->newLine();
            $this->info("========================================");
            $this->info("🔑 TOKEN JWT BEARER GERADO COM SUCESSO!");
            $this->info("========================================");
            $this->newLine();
            
            $this->line("📋 Credenciais do usuário:");
            $this->line("   Email: $email");
            $this->line("   Senha: $password");
            $this->newLine();
            
            $this->line("🔐 Token JWT Bearer:");
            $this->warn($token);
            $this->newLine();
            
            $this->info("📖 Como usar no Swagger:");
            $this->line("1. Acesse: http://127.0.0.1:8000/api/documentation");
            $this->line("2. Clique no botão 'Authorize' (cadeado) no topo");
            $this->line("3. Cole o token acima no campo 'Value'");
            $this->line("4. Clique em 'Authorize' e depois 'Close'");
            $this->newLine();
            
            $this->info("⏱️  Validade: " . (Auth::guard('api')->factory()->getTTL()) . " minutos");
            $this->info("========================================");
            
            return Command::SUCCESS;
        } else {
            $this->error("Erro ao gerar o token!");
            return Command::FAILURE;
        }
    }
}
