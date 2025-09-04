<?php

namespace App\Services\Setup;

use App\Http\Resources\Settings\EmailSettingsResource;
use App\Http\Resources\Settings\GeneralSettingsResource;
use App\Http\Resources\Settings\LiveChatSettingsResource;
use App\Http\Resources\Settings\SocialiteSettingsResource;
use App\Models\Setup\GeneralSettings;
use App\Models\Setup\Imap;
use App\Models\Setup\OpenAiSetting;
use App\Models\Setup\SmtpUser;
use App\Models\Setup\SocialCredential;
use App\Models\Setup\Sources;
use App\Models\Setup\Status;
use App\Repositories\Setup\SetupRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;

class SetupService
{
    public function __construct(protected SetupRepository $setupRepository) {}

    public function menuSettings(): array
    {
        $currentRouteName = Route::currentRouteName();

        return [
            [
                'category' => 'General',
                'items' => [
                    [
                        'name' => 'General',
                        'icon' => 'heroicons:cog-6-tooth',
                        'href' => route('setup.general.index', 'general'),
                        'active' => request()->url() === route('setup.general.index', 'general'),
                    ],
                    [
                        'name' => 'Localization',
                        'icon' => 'heroicons:globe-alt',
                        'href' => route('setup.general.index', 'localization'),
                        'active' => request()->url() === route('setup.general.index', 'localization'),
                        'hasNotification' => false,
                    ],
                    [
                        'name' => 'Email',
                        'icon' => 'heroicons:envelope',
                        'href' => route('setup.general.index', 'email'),
                        'active' => request()->url() === route('setup.general.index', 'email'),
                        'hasNotification' => false,
                    ],
                ],
            ],
            [
                'category' => 'APPs',
                'items' => [
                    [
                        'name' => 'PWA',
                        'icon' => 'material-symbols-light:apk-document-rounded',
                        'href' => route('setup.general.index', 'pwa'),
                        'active' => request()->url() === route('setup.general.index', 'pwa'),
                    ],
                ],
            ],
            [
                'category' => 'Configure Features',
                'items' => [
                    [
                        'name' => 'Leads',
                        'icon' => 'heroicons:document-currency-dollar',
                        'href' => route('setup.general.index', 'lead'),
                        'active' => request()->url() === route('setup.general.index', 'lead'),
                    ],
                ],
            ],
            [
                'category' => 'Integrations',
                'items' => [
                    [
                        'name' => 'Google',
                        'icon' => 'cbi:google-logo-circle',
                        'href' => route('setup.general.index', 'google'),
                        'active' => request()->url() === route('setup.general.index', 'google'),
                    ],
                    [
                        'name' => 'Microsoft',
                        'icon' => 'prime:microsoft',
                        'href' => route('setup.general.index', 'microsoft'),
                        'active' => request()->url() === route('setup.general.index', 'microsoft'),
                    ],
                    [
                        'name' => 'Pusher/Ably',
                        'icon' => 'material-symbols:circle-notifications',
                        'href' => route('setup.general.index', 'notification'),
                        'active' => request()->url() === route('setup.general.index', 'notification'),
                    ],
                ],
            ],
            [
                'category' => 'AI Integration',
                'items' => [
                    [
                        'name' => 'Open AI',
                        'icon' => 'heroicons:at-symbol',
                        'href' => route('setup.general.index', 'openai'),
                        'active' => request()->url() === route('setup.general.index', 'openai'),
                    ],
                ],
            ],
        ];
    }

    public function propsGeneral($type): array
    {
        return match ($type) {
            'general' => [
                'title' => 'General Settings',
                'generalSettings' => new GeneralSettingsResource(GeneralSettings::query()->first()),
            ],
            'localization' => [
                'title' => 'Localization Settings',
                'timezones' => timezones(),
                'dateFormats' => dateFormats(),
                'timeFormat' => timeFormat(),
                'generalSettings' => new GeneralSettingsResource(GeneralSettings::query()->first()),
            ],
            'email' => [
                'title' => 'Email Settings',
                'emailSettings' => new EmailSettingsResource($this->setupRepository->getEmailSettings()),
            ],
            'pwa' => [
                'title' => 'Pwa Settings',
                'generalSettings' => new GeneralSettingsResource(GeneralSettings::query()->first()),
            ],
            'lead' => [
                'title' => 'Lead Settings',
            ],
            'google' => [
                'title' => 'Google Settings',
                'google' => new SocialiteSettingsResource($this->setupRepository->getSocialiteSettings('google')),
            ],
            'microsoft' => [
                'title' => 'Microsoft Settings',
                'microsoft' => new SocialiteSettingsResource($this->setupRepository->getSocialiteSettings('microsoft')),
            ],
            'notification' => [
                'title' => 'Pusher/Ably',
                'liveChatSettings' => new LiveChatSettingsResource($this->setupRepository->getChatSettings()),
            ],
            'openai' => [
                'title' => 'Openai Settings',
                'openAiSettings' => $this->setupRepository->getOpenAiSettings(),
            ],
            default => [
                'title' => 'Unknown Section',
            ],
        };
    }

    public function mailsConfigureLogo(): array
    {
        $gmail = $this->getSocialCredentials('gmail');
        $g = $this->jsonToArray($gmail?->credentials);

        $outlook = $this->getSocialCredentials('outlook');
        $o = $this->jsonToArray($outlook?->credentials);

        return [
            'Gmail' => [
                'routeName' => route('auth.login.socialite', 'google'),
                'icon' => asset('assets/icons/gmail.png'),
                'isConfigure' => (bool) $g['email'],
                'email' => $g['email'],
                'color' => 'bg-red-50',
            ],
            'Outlook' => [
                'routeName' => route('auth.login.socialite', 'outlook'),
                'icon' => asset('assets/icons/outlook.png'),
                'isConfigure' => (bool) $o['email'],
                'email' => $o['email'],
                'color' => 'bg-cyan-50',
            ],
            'WebMail' => [
                'routeName' => route('employee.imap.settings', 'webmail'),
                'icon' => asset('assets/icons/webmail.png'),
                'isConfigure' => (bool) $this->getImap('webmail')->type,
                'email' => $this->getImap('webmail')->imap_user_name,
                'color' => 'bg-blue-50',
            ],
            'AppleMail' => [
                'routeName' => route('employee.imap.settings', 'applemail'),
                'icon' => asset('assets/icons/apple.svg'),
                'isConfigure' => (bool) $this->getImap('applemail')->type,
                'email' => $this->getImap('applemail')->imap_user_name,
                'color' => 'bg-green-50',
            ],
        ];
    }

    public function saveImap(array $data): Model|Imap
    {
        return Imap::query()->updateOrCreate(
            [
                'user_id' => auth()->id(),
                'type' => $data['type'],
            ],
            [
                'imap_server' => $data['imap_server'],
                'imap_user_name' => $data['imap_user_name'],
                'password' => $data['password'],
                'imap_port' => $data['imap_port'],
                'folder' => $data['folder'],
                'imap_encryption' => $data['imap_encryption'],
                'type' => $data['type'],
                'user_id' => auth()->id(),
            ]
        );
    }

    public function getImap($type): Model|Imap
    {
        return Imap::query()->where(['user_id' => auth()->id(), 'type' => $type])->first() ?? new Imap();
    }

    public function getSocialCredentials($provider): SocialCredential|Imap
    {
        return SocialCredential::query()->where(['user_id' => auth()->id(), 'provider' => $provider])->first() ?? new SocialCredential();
    }

    public function jsonToArray(?string $json = null): array
    {
        if (empty($json)) {
            return [
                'email' => '',
            ];
        }

        return json_decode($json, true);
    }

    public function saveSmtpUser($data): SmtpUser|Model
    {
        $smtp = SmtpUser::query()->where('user_id', auth()->id())->first();

        return SmtpUser::query()->updateOrCreate(
            ['id' => $smtp->id ?? null],
            array_merge($data, ['user_id' => auth()->id()])
        );
    }

    public function saveOpenAiSettings(array $data): OpenAiSetting|Model
    {
        $settings = OpenAiSetting::query()->first() ?? new OpenAiSetting();

        return OpenAiSetting::query()->updateOrCreate(
            ['id' => $settings->id ?? null],
            $data
        );
    }

    public function saveSource($data): Model|Sources
    {
        return Sources::query()->updateOrCreate(
            ['id' => $data['id'] ?? null],
            $data
        );
    }

    public function saveStatus($data): Model|Status
    {
        return Status::query()->updateOrCreate(
            ['id' => $data['id'] ?? null],
            $data
        );
    }
}
